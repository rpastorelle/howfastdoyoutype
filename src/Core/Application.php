<?php
namespace Core;


use App\Models\User;
use Aura\Sql\ExtendedPdo;
use Aura\SqlQuery\QueryFactory;
use Core\Database\Model;
use Core\Http\Exception as HttpException;
use Interop\Container\ContainerInterface;
use Slim\App;

/**
 * Core Application
 * @property-read array                         envData
 * @property-read \Aura\Sql\ExtendedPdo         db
 * @property-read \Aura\SqlQuery\QueryFactory   query
 * @property-read \Slim\Views\Twig              view
 */
class Application extends App {

    /**
     * The environment data used for all template rendering
     * @var array
     */
    public $envData;

    /**
     * @var \App\Models\User
     */
    protected $currentUser;

    /**
     * Lazy DB connection
     * @var  \Aura\Sql\ExtendedPdo
     */
    public $db;

    /**
     * @var  \Aura\SqlQuery\QueryFactory
     */
    public $query;

    /**
     * @var  \Hashids\Hashids
     */
    public $hashids;

    /**
     * @param ContainerInterface|array $container
     */
    public function __construct($container = [])
    {
        parent::__construct($container);
        $this->getContainer()['app'] = function () { return $this; };

        $this->setupServices();
        $this->setupEnvData();
        $this->loadRoutes();
        $this->setupNotFound();
        $this->setupErrorHandler();

        Model::setApp($this);
    }

    /**
     * Gets a setting or returns the default.
     * @param $key
     * @param null $default
     * @return null
     */
    public function getSetting($key, $default = null)
    {
        // @SLIM3
        $settings = $this->getContainer()->get('settings');
        if (! isset($settings[$key])) {
            return $default;
        }
        return $settings[$key];
    }

    public function isProd()
    {
        return ($this->getSetting('env') === 'prod');
    }

    public function isApi()
    {
        // @SLIM3
        $uri = $this->getContainer()->request->getUri();
        $path = $uri->getPath();
        return (stripos($path, '/api') === 0);
    }


    /**
     * Get the current user.
     * @return User
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * Set the current user.
     * @param User $user
     */
    public function setCurrentUser($user = null)
    {
        $this->currentUser = $user;
    }

    public function validateToken($token)
    {
        return true;
    }

    private function setupEnvData()
    {
        $this->envData = [
            'app_name' => $this->getSetting('app.name'),
            'ga_tracking_id' => $this->getSetting('ga.tracking_id'),
        ];
    }

    /**
     * Load the routes file
     */
    private function loadRoutes()
    {
        $routes = $this->loadConfigFile('routes');
        if (! $routes) {
            throw new \RuntimeException('Missing routes file.');
        }
    }

    /**
     * Service Definitions
     */
    private function setupServices()
    {
        $container = $this->getContainer();

        $container['view'] = function ($c) {
            $view = new \Slim\Views\Twig($c['settings']['base_path'].'/resources/templates');
            $view->addExtension(new \Slim\Views\TwigExtension(
                $c['router'],
                $c['request']->getUri()
            ));
            return $view;
        };

        $container['db'] = function ($c) {
            $db = new ExtendedPdo(
                'mysql:host='.$c['settings']['db.host'].';dbname='.$c['settings']['db.name'],
                $c['settings']['db.user'],
                $c['settings']['db.pass']
            );
            return $db;
        };
        $this->db = $container['db'];

        $container['query'] = function ($c) {
            $query = new QueryFactory('mysql');
            return $query;
        };
        $this->query = $container['query'];
    }

    private function setupNotFound()
    {
        if ($this->isApi()) {
            $this->setupApiNotFound();
            return;
        }

        $container = $this->getContainer();
        //Override the default Not Found Handler
        $container['notFoundHandler'] = function ($c) {
            return function ($request, $response) use ($c) {
                // @SLIM3
                return $this->notFound($c);
            };
        };
    }

    // @SLIM3
    private function notFound($c)
    {
        return $c['view']
            ->render($c['response'], 'errors/404.html', $this->getErrorTemplateData())
            ->withStatus(404);
    }

    private function setupErrorHandler()
    {
        if ($this->isApi()) {
            $this->setupApiErrorHandler();
            return;
        }

        $container = $this->getContainer();
        $container['errorHandler'] = function ($c) {
            return function ($request, $response, \Exception $e) use ($c) {
                if ($e instanceof HttpException) {
                    if ($e->getStatusCode() === 404) {
                        // @SLIM3
                        return $this->notFound($c);
                    }
                    return $c['view']
                                ->render($c['response'], 'errors/http-error.html', $this->getErrorTemplateData([
                                    'code' => $e->getStatusCode(),
                                    'message' => $e->getMessage(),
                                ]))
                                ->withStatus($e->getStatusCode());
                } else {
                    return $c['view']
                                ->render($c['response'], 'errors/500.html', $this->getErrorTemplateData())
                                ->withStatus(500);
                }
            };
        };
    }

    private function setupApiNotFound()
    {
        $c = $this->getContainer();
        $c['notFoundHandler'] = function ($c) {
            return function ($request, $response, $e) use ($c) {
                $statusCode = $e->getStatusCode();
                if (! $statusCode) $statusCode = 500;
                return $c['response']->withJson($e->toArray())->withStatus($statusCode);
            };
        };
    }

    private function setupApiErrorHandler()
    {
        $c = $this->getContainer();
        $c['errorHandler'] = function ($c) {
            return function ($request, $response, $e) use ($c) {
                var_dump($e); die;
                $statusCode = $e->getStatusCode();
                if (! $statusCode) $statusCode = 500;
                return $c['response']->withJson($e->toArray())->withStatus($statusCode);
            };
        };
    }


    /**
     * Gets template data for error handling pages
     * @return array template data
     */
    private function getErrorTemplateData()
    {
        return [
          'env'  => $this->envData,
          'meta' => [
              'title' => $this->getSetting('app.name'),
          ],
        ];
    }

    /**
     * Load a config file.
     * @param $file
     * @return bool|mixed
     */
    private function loadConfigFile($file)
    {
        $file = $this->getSetting('base_path').'/config/'.$file.'.php';
        return is_file($file) ? require($file) : false;
    }
}