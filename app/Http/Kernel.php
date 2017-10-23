<?php

namespace App\Http;

use App\Models\Group;

class Kernel
{
    /**
     * The application's global middleware stack
     * These middleware are run during every request to your application
     *
     * @var array
     */
    protected static $middleware = [];

    /**
     * The application's route middleware
     * These middleware are used individually on current sections
     *
     * @var array
     */
    protected static $routeMiddleware = [];

    /**
     * Initialize middleware.
     */
    protected function __construct()
    {
        self::setMiddleware();
        self::setRouteMiddleware();
    }

    /**
     * Set the application's global middleware stack.
     *
     * @return void
     */
    protected function setMiddleware()
    {
        self::$middleware = [
            'DeveloperMode',
            'InputValidation',
        ];
    }

    /**
     * Set The application's route middleware.
     *
     * @return void
     */
    protected function setRouteMiddleware()
    {
        self::$routeMiddleware = [
            'home' => [
                'Authenticate',
                //'SomeMiddleware'
            ],
            'home.index' => [
                'Guard' => [
                    Group::Admin(),
                ]
            ],
            'login' => [
                'NotAuthenticate',
            ],
        ];
    }

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
        // Initialize middleware.
        new self;

        // Validate global middleware
        foreach(self::$middleware as $key => $value) {
            if (is_array($value)) {
                $middlewareTest = $key;
                $args           = $value;
            } else {
                $middlewareTest = $value;
                $args           = [];
            }

            $middlewareTestClass = '\\App\Http\Middleware\\'. $middlewareTest;
            if (!self::checkMiddleware($middlewareTestClass, $args)) {
                return false;
            }
        }

        // Validate controller section
        if (isset(self::$routeMiddleware[$controller])) {
            foreach(self::$routeMiddleware[$controller] as $key => $value) {
                if (is_array($value)) {
                    $middlewareTest = $key;
                    $args           = $value;
                } else {
                    $middlewareTest = $value;
                    $args           = [];
                }

                $middlewareTestClass = '\\App\Http\Middleware\\'. $middlewareTest;
                if (!self::checkMiddleware($middlewareTestClass, $args)) {
                    return false;
                }
            }
        }

        // Validate controller's method section
        if (isset(self::$routeMiddleware[$controller .'.'. $method])) {
            foreach(self::$routeMiddleware[$controller .'.'. $method] as $key => $value) {
                if (is_array($value)) {
                    $middlewareTest = $key;
                    $args           = $value;
                } else {
                    $middlewareTest = $value;
                    $args           = [];
                }

                $middlewareTestClass = '\\App\Http\Middleware\\'. $middlewareTest;
                if (!self::checkMiddleware($middlewareTestClass, $args)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Validate middleware.
     *
     * @param string $middlewareTestClass       middleware class name including namespace
     * @param array $parameters
     * @return bool
     */
    private static function checkMiddleware($middlewareTestClass, $parameters = [])
    {
        new $middlewareTestClass;
        if ($middlewareTestClass::validate($parameters)) {
            return true;
        }

        return false;
    }
}
