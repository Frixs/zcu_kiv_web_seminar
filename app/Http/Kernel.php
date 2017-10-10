<?php
//TODO create some basic middleware https://github.com/laravel/laravel/blob/master/app/Http/Kernel.php
namespace App\Http;

class Kernel
{
    protected static $middleware = [
    ];

    protected static $middlewareGroups = [
        'web' => [
        ]
    ];

    protected static $routeMiddleware = [
        'home' => 'Authenticate',
        'home.index' => 'Authenticate'
    ];

    /**
     * Run all middlewares according to input parameters
     *
     * @param string $controller
     * @param string $method
     * @param array $parameters
     * @return bool
     */
    public static function run($controller, $method, $parameters)
    {
        if (isset(self::$routeMiddleware[$controller])) {
            $validator = '\\App\Http\Middleware\\'. self::$routeMiddleware[$controller];
            new $validator;
            if (!$validator::validate()) {
                return false;
            }
        }

        if (isset(self::$routeMiddleware[$controller .'.'. $method])) {
            $validator = '\\App\Http\Middleware\\'. self::$routeMiddleware[$controller .'.'. $method];
            if (!$validator::validate()) {
                return false;
            }
        }

        return true;
    }
}
