<?php
// Load Env Config
(new Dotenv\Dotenv('../'))->load();
$env = strtolower(getenv('ENV'));
$site_url = 'http://howfastdoyoutype.com/';

switch ($env) {
    case 'dev':
        $site_url = 'http://dev.howfastdoyoutype.com/';
        break;
}

$isProd = ($env === 'prod');
$container = new Slim\Container([
    'settings' => [
        'env' => $env,
        'base_path' => realpath(__DIR__.'/../'),

        'app.name'        => 'How Fast Do You Type?',
        'app.description' => 'How fast do you type is a fun typing game to test your words per minute (wpm). Find out how your typing skills compare.',
        'app.keywords'    => 'words per minute,typing,speed,typing test',
        'app.urls.site'   => $site_url,

        'db.host'    => getenv('DB_HOST'),
        'db.name'    => getenv('DB_NAME'),
        'db.user'    => getenv('DB_USER'),
        'db.pass'    => getenv('DB_PASS'),
        'db.charset' => 'utf8',

        'ga.tracking_id' => $isProd ? 'UA-30846975-1' : null,
    ],
]);

return $container;