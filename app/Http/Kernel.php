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
        return true;
    }
}
