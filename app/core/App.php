<?php

/**
 * Router
 */
class App
{
    /**
     * Default controller name
     *
     * @var string
     */
    protected $controller = 'home';

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
    protected $params = [];

    /**
     * Postfix of the controllers
     *
     * @var string
     */
    private $controllerPostfix = 'Controller';

    /**
     * The main contructor
     */
    public function __construct()
    {
        $url = $this->parseUrl();

        // check if requested controller exists
        if (file_exists('../app/controllers/'. $url[0] . $this->controllerPostfix .'.php')) {
            $this->controller = $url[0];
            unset($url[0]);
        }
        
        $controllerFullName = $this->convertToStudlyCaps($this->controller) . $this->controllerPostfix;

        require_once '../app/controllers/'. $controllerFullName .'.php';
        
        $this->controller = new $controllerFullName();

        // check if the next parameter exists
        if (isset($url[1])) {
            // if it is method, grab it
            if (method_exists($this->controller, $url[1])) {
                $reflection = new ReflectionMethod($this->controller, $url[1]);
                if ($reflection->isPublic()) {
                    $this->method = $url[1];
                    unset($url[1]);
                }
            }
        }

        // rest of the url are method parameters
        $this->params = $url ? array_values($url) : [];

        // call the controller's method
        call_user_func_array([$this->controller, $this->method], $this->params);
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
}
