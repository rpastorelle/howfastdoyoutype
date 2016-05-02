<?php
namespace Core\Database;

use Core\Application;

class Model
{
    /**
     * @var Application
     */
    protected static $app;

    /**
     * @param Application $app
     */
    public static function setApp(Application $app) {
        static::$app = $app;
    }
}