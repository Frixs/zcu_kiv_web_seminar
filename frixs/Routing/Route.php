<?php

namespace Frixs\Routing;

use App\Http\Kernel;

class Route
{
    /**
     * The URL pattern the route responds to
     *
     * @var array
     */
    public $url;

    /**
     * Default controller name
     *
     * @var string
     */
    protected $controller = 'home';

    /**
     * Controller object
     *
     * @var object
     */
    protected $controllerInstance = null;
    
    /**
     * Default controller's method
     *
     * @var string
     */
    protected $method = 'index';
    
    /**
     * Default parrameters for the method
     *
     * @var array
     */
    protected $parameters = [];
    
    /**
     * Postfix of the controllers
     *
     * @var string
     */
    private $controllerPostfix = 'Controller';

    /**
     * Create a new Route instance
     */
    public function __construct()
    {
        $this->url = $this->parseUrl();

        $this->parseController();
        
        $controllerFullName = '\\App\\Http\\Controllers\\Pages\\'. $this->getControllerFullName();

        $this->controllerInstance = new $controllerFullName();

        $this->parseMethod();

        // rest of the url are method parameters
        $this->bindParameters();

        // Run the mail validation tests (middleware)
        if (Kernel::run($this->controller, $this->method, $this->parameters)) {
            // call the controller's method
            $this->callControllerMethod($this->controllerInstance, $this->method, $this->parameters);
        }
    }

    /**
     * Method parse the URL to the array
     *
     * @return array
     */
    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/')));
        }
    }

    /**
     * Convert the string with hyphens to camelCase
     * f.e. something-like-that => somethingLikeThat
     *
     * @param string $string    input string to convert
     * @return string           formatted string
     */
    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Convert the string with hyphens to StudlyCaps,
     * f.e. something-like-that => SomethingLikeThat
     *
     * @param string $string    the string to convert
     * @return string           formatted string
     */
    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Get current requested controller
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Get current requested controller instance
     *
     * @return string
     */
    public function getControllerInstance()
    {
        return $this->controllerInstance;
    }

    /**
     * Get current requested controller in the correct format
     *
     * @return void
     */
    protected function getControllerFullName()
    {
        return $this->convertToStudlyCaps($this->controller) . $this->controllerPostfix;
    }

    /**
     * Parse controller from the URL
     *
     * @return void
     */
    protected function parseController()
    {
        if (file_exists('../app/Http/Controllers/Pages/'. $this->url[0] . $this->controllerPostfix .'.php')) {
            $this->controller = $this->url[0];
            unset($this->url[0]);
        }
    }

    /**
     * Call the controller's method with parameters
     *
     * @param object $controller
     * @param string $method
     * @param array $parameters
     * @return void
     */
    protected function callControllerMethod($controller, $method, $parameters = [])
    {
        call_user_func_array([$controller, $method], $parameters);
    }

    /**
     * Get current requested method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Parse method from the URL
     *
     * @return void
     */
    protected function parseMethod()
    {
        // check if the next parameter exists
        if (isset($this->url[1])) {
            // if it is method, grab it
            if ($this->methodExists($this->controllerInstance, $this->url[1])) {
                if ($this->isMethodPublic($this->controllerInstance, $this->url[1])) {
                    $this->method = $this->url[1];
                    unset($this->url[1]);
                }
            }
        }
    }

    /**
     * Check if method is public in the controller
     *
     * @param string $controller
     * @param string $method
     * @return boolean
     */
    public function isMethodPublic($controller, $method)
    {
        $reflection = new \ReflectionMethod($controller, $method);
        if ($reflection->isPublic()) {
            return true;
        }

        return false;
    }

    /**
     * Check if method exists in the controller
     *
     * @param string $controller
     * @param string $method
     * @return void
     */
    public function methodExists($controller, $method)
    {
        return method_exists($controller, $method);
    }

    /**
     * Check if URL has any next parameters
     *
     * @return boolean
     */
    protected function hasParameters()
    {
        return $this->url ? true : false;
    }

    /**
     * Bind parameters
     *
     * @return void
     */
    protected function bindParameters()
    {
        $this->parameters = $this->hasParameters() ? array_values($this->url) : [];
    }
}
