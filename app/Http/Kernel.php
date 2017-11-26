<?php

namespace App\Http;

use App\Models\Group;

use Frixs\Auth\Guard;

class Kernel
{
    /*
     |  Kernel's attributes.
     |  ==============================================
     |
     |
     |
     |________________________________________________
    */

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
     * The request middleware aliases
     *
     * @var array
     */
    protected static $requestMiddleware = [];

    /**
     * Guard's permissions.
     *
     * @var array
     */
    protected static $guardPermissions = [];

    /*
     |  Initialization of the Kernel config arrays.
     |  ==============================================
     |
     |
     |
     |________________________________________________
    */

    /**
     * Initialize middleware.
     */
    protected function __construct()
    {
        // Initialize settings.
        self::setMiddleware();
        self::setRouteMiddleware();
        self::setRequestMiddleware();
        self::setGuardPermissions();
    }

    /*
     |  The Kernel config arrays.
     |  ==============================================
     |
     |
     |
     |________________________________________________
    */

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
                //'SomeMiddlewareName'
            ],
            'login' => [
                'NotAuthenticate',
            ],
            'register' => [
                'NotAuthenticate',
            ],
            'dashboard' => [
                'Authenticate',
            ],
            'server' => [
                'Authenticate',
            ],
            'server.index' => [
                'GuardAny' => [
                    Group::SMember(),
                    Group::SRecruit(),
                ],
            ],
        ];
    }

    /**
     * Set request middleware aliases to check.
     *
     * @return void
     */
    protected function setRequestMiddleware()
    {
        self::$requestMiddleware = [
            'server-create' => [
                'server.create',
            ],
        ];
    }

    /**
     * Set guard's permissions.
     *
     * @return void
     */
    protected function setGuardPermissions()
    {
        self::$guardPermissions = [
            'server' => [
                'settings' => [
                    'basics' => [
                        Group::SMaster(),
                    ],
                ],
            ],
        ];
    }

    /*
     |  Kernel's methods.
     |  ==============================================
     |
     |
     |
     |________________________________________________
    */

    /**
     * Run all middleware according to input parameters
     *
     * @param string $controller
     * @param string $method
     * @param array $parameters
     * @return boolean
     */
    public static function run($controller, $method, $parameters, $isRequest = false)
    {
        // Initialize middleware.
        new self;

        // Initialize Guard's permissions.
        Guard::getInstance(self::$guardPermissions);

        // Validate global middleware
        if (!self::validateGlobalMiddleware()) {
            return false;
        }
        
        // Validate Request if it is concerned.
        if ($isRequest) {
            if (isset(self::$requestMiddleware[$controller])) {
                for ($i = 0; $i < count(self::$requestMiddleware[$controller]); $i++) {
                    $pieces             = explode('.', self::$requestMiddleware[$controller]);
                    $controllerSection  = $pieces[0];
                    $methodSection      = isset($pieces[1]) ? $pieces[1] : '';

                    // Validate controller section
                    if (!self::validateControllerSection($controllerSection)) {
                        return false;
                    }

                    // Validate controller's method section
                    if (!self::validateMethodSection($controllerSection, $methodSection)) {
                        return false;
                    }
                }
            }
        } else {
            // Validate controller section
            if (!self::validateControllerSection($controller)) {
                return false;
            }

            // Validate controller's method section
            if (!self::validateMethodSection($controller, $method)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate global middleware.
     *
     * @return boolean
     */
    private static function validateGlobalMiddleware() {
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

        return true;
    }

    /**
     * Validate controller section.
     *
     * @param string $controller
     * @return boolean
     */
    private static function validateControllerSection($controller) {
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

        return true;
    }

    /**
     * Validate controller's method section.
     *
     * @param string $controller
     * @param string $method
     * @return boolean
     */
    private static function validateMethodSection($controller, $method) {
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
     * @return boolean
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
