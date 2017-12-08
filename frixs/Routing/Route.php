<?php

namespace Frixs\Routing;

use App\Http\Kernel;
use Frixs\Routing\RouteRequest;
use Frixs\Routing\Router;
use Frixs\Language\Lang;

class Route
{
    /**
     * Singleton
     *
     * @var object
     */
    private static $_instance = null;

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
    protected $controllerPostfix = 'Controller';

    /**
     * Create a new Route instance
     */
    private function __construct()
    {
        // check if URL contains request execution.
        if (RouteRequest::getInstance()->requestExists()) {
            return;
        }

        // Parse the URL address to an array.
        $this->url = $this->parseUrl();

        // Parse the next parameter of the URL and check if Controller exists.
        $this->parseController();
        
        // Put name of the controller into a full name.
        $controllerFullName = '\\App\\Http\\Controllers\\Pages\\'. $this->getControllerFullName();

        // Create the controller's instance
        $this->controllerInstance = new $controllerFullName();

        // Parse the next parameter of the URL and check if method exists in your controller.
        $this->parseMethod();

        // Rest of the url are method parameters.
        $this->bindParameters();

        // Run the mail validation tests (middleware)
        if (Kernel::run($this->controller, $this->method, $this->parameters)) {
            // call the controller's method
            $this->callControllerMethod($this->controllerInstance, $this->method, $this->parameters);
        }
    }

    /**
     * Singleton model object, get instance of it.
     *
     * @return object
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Route();
        }
        return self::$_instance;
    }

    /**
     * Method parse the URL to the array
     *
     * @return array
     */
    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            $arrGET         = $_GET;

            // Save URL.
            $url            = $arrGET['url'];
            // Save dynamically added parameters with '?' or '&' delimiter.
            $pramsAsKeys    = array_keys($arrGET);
            // Remove the first value from the array - 'url' - We want only the parameters. The first is always 'url'.
            array_shift($pramsAsKeys);
            // Implode the paramters to our format.
            $params         = implode('/', $pramsAsKeys);

            // Stick it together with the URL.
            $url .= $params ? '/'. $params : '';

            return preg_split( "/(\/|\?|&)/", filter_var(rtrim($url, '/')));
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
     * @param string $name
     * @return void
     */
    protected function getControllerFullName($name = null)
    {
        $controllerName = $this->controller;
        if (isset($name)) {
            $controllerName = $name;
        }

        return $this->convertToStudlyCaps($controllerName) . $this->controllerPostfix;
    }

    /**
     * Parse controller from the URL
     *
     * @return void
     */
    protected function parseController()
    {
        $controllerFullname = $this->getControllerFullName((isset($this->url[0]) ? $this->url[0] : ''));

        if (file_exists('../app/Http/Controllers/Pages/'. $controllerFullname .'.php')) {
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
            $methodName = $this->convertToCamelCase($this->url[1]);
            // if it is method, grab it
            if ($this->methodExists($this->controllerInstance, $methodName)) {
                if ($this->isMethodPublic($this->controllerInstance, $methodName)) {
                    $this->method = $methodName;
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
        $this->parameters = $this->hasParameters() ? $this->parseParameters() : [];
    }

    /**
     * Parse parameters, assign key to them if have one.
     * example: /par1/par2/par3key:par3/par4/par5key:par5
     *
     * @return array
     */
    private function parseParameters()
    {
        $params = [];

        foreach (array_values($this->url) as $item) {
            if (strpos($item, ':') === false) {
                $params[] = $item;
                continue;
            }
        
            $item = explode(':', $item);
            // Check acceptable form of the array key.
            if (!preg_match('/^[a-zA-Z0-9\_]+$/', $item[0])) {
                Router::redirectToError(500, Lang::get('error.inappropriate_parameter_key', ['key' => $item[0]]));
            }
            // --- Addon to recognize server room.
            // TOOD: for setup new project
            $serverKeyName = 'server';
            if ($item[0] === $serverKeyName && is_numeric($item[1])) {
                \App\Models\Server::setServerID($item[1]);
                // Parameter 'server' is ignored for output params.
                continue;
            }
            // ---
            $params[$item[0]] = $item[1];
        }
        
        return $params;
    }
}
