<?php

namespace App\Http;

class Kernel
{
    /**
     * The application's global middleware stack
     * These middleware are run during every request to your application
     *
     * @var array
     */
    protected static $middleware = [
        //'SomeMiddleware',
        //'SomeMiddleware',
        //'SomeMiddleware'
    ];

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $middlewareGroups = [
        'web' => [
        ]
    ];

    /**
     * The application's route middleware
     * These middleware are used individually on current sections
     *
     * @var array
     */
    protected static $routeMiddleware = [
        'home' => 'Authenticate',
        //'home.index' => ''
    ];

    /**
     * Run all middleware according to input parameters
     *
     * @param string $controller
     * @param string $method
     * @param array $parameters
     * @return bool
     */
    public static function run($controller, $method, $parameters)
    {
        // Validate global middleware
        for ($i = 0; $i < count(self::$middleware); $i++) {
            $middlewareTestClass = '\\App\Http\Middleware\\'. self::$middleware[$i];
            if (!self::checkMiddleware($middlewareTestClass)) {
                return false;
            }
        }

        // Validate controller section
        if (isset(self::$routeMiddleware[$controller])) {
            $middlewareTestClass = '\\App\Http\Middleware\\'. self::$routeMiddleware[$controller];
            if (!self::checkMiddleware($middlewareTestClass)) {
                return false;
            }
        }

        // Validate controller's method section
        if (isset(self::$routeMiddleware[$controller .'.'. $method])) {
            $middlewareTestClass = '\\App\Http\Middleware\\'. self::$routeMiddleware[$controller .'.'. $method];
            if (!self::checkMiddleware($middlewareTestClass)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate middleware
     *
     * @param string $middlewareTestClass   middleware class name including namespace
     * @return bool
     */
    private static function checkMiddleware($middlewareTestClass)
    {
        new $middlewareTestClass;
        if ($middlewareTestClass::validate()) {
            return true;
        }

        return false;
    }
}
