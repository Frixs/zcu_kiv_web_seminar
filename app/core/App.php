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
        
        $controllerFullName = $this->controller . $this->controllerPostfix;

        require_once '../app/controllers/'. $controllerFullName .'.php';
        
        $this->controller = new $controllerFullName();

        // check if next parameter exists
        if (isset($url[1])) {
            // if it is method, grab it
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
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
}
