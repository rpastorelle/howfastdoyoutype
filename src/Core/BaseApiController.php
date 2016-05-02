<?php
namespace Core;

use Core\Http\Exception;
use Core\Http\Exception\NotFoundException;
use Slim\Container;

/**
 * Class BaseController
 * @property Application    app
 * @property array          metadata
 * @package Core
 */
class BaseApiController extends BaseController
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var array|mixed
     */
    protected $jsonParams = [];

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->app = $container['app'];

        $this->jsonParams = $this->getJsonParams();
    }

    /**
     * Not Found Route
     * @return $response
     */
    public function notFound()
    {
        throw new NotFoundException('Route not found');
    }

    /**
     * Send a Successful response with the given data. This will automatically
     * call toArray on $data if it is an abject that has the method.
     * @param $payload
     */
    protected function success($payload)
    {
        if (is_object($payload) && method_exists($payload, 'toArray')) {
            $payload = [
                'data' => $payload->toArray(),
            ];
        } else {
            $payload = [
                'data' => $payload,
            ];
        }

        return $this->sendJsonResponse($payload);
    }

    /**
     * Sends the given JSON response.
     * @param array $data
     * @param int $status
     * @throws \Slim\Exception\Stop
     */
    protected function sendJsonResponse(array $data, $status = 200)
    {
        $isOk = $status >= 200 && $status < 300;
        // We merge here so that you can override this in the $data array.
        $data = array_merge(['ok' => $isOk], $data);
        return $this->container->response->withJson($data);
    }

    private function getJsonParams()
    {
        if (strpos($this->container->request->getContentType(), 'application/json') === false) {
            return [];
        }

        $jsonParams = json_decode($this->container->request->getBody(), true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $jsonParams;
        }

        // if we get to here, then there was an error decoding the JSON, so let's handle that.
        $errString = 'Unknown Error';
        switch (json_last_error()) {
            case JSON_ERROR_DEPTH:
                $errString = 'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $errString = 'Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $errString = 'Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                $errString = 'Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                $errString = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
        }

        throw new Exception('JSON Decode Error: '.$errString);
    }

    /**
     * @return \App\Models\User|null
     */
    protected function getCurrentUser()
    {
        return $this->app->getCurrentUser();
    }

    /**
     * Get a JSON Param.
     *
     * @param      $key
     * @param null $default
     * @return mixed
     */
    protected function json($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->jsonParams;
        }
        return array_get($this->jsonParams, $key, $default);
    }
    /**
     * Get a GET/POST/JSON Param.
     *
     * @param      $key
     * @param null $default
     * @return mixed
     */
    protected function params($key = null, $default = null)
    {
        $union = array_merge($this->container->request->get(), $this->container->request->post());
        if (is_array($this->jsonParams)) {
            $union = array_merge($union, $this->jsonParams);
        }
        if ($key) {
            return array_get($union, $key, $default);
        }
        return $union;
    }
}