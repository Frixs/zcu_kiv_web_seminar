<?php

namespace Frixs\Routing;

use Frixs\Routing\Router;
use Frixs\Language\Lang;
use Frixs\Http\Input;

class RouteRequest extends Route
{
    /**
     * Default request name
     *
     * @var string
     */
    protected $controller = null;
    
    /**
     * Default request's method
     *
     * @var string
     */
    protected $method = 'process';
    
    /**
     * Postfix of the requests
     *
     * @var string
     */
    protected $controllerPostfix = 'Request';

    /**
     * Request identifier
     *
     * @var string
     */
    protected $requestIdentifier = '_request';

    /**
     * Bool of request existance.
     *
     * @var boolean
     */
    protected $requestExists = false;

    /**
     * Create a new RouteRequest instance
     */
    public function __construct()
    {
        // First of all, check legitimity of access to the request.
        if (!$this->isAccessLegit()) {
            return;
        }

        // Parse the URL address to an array.
        $this->url = $this->parseUrl();

        // Parse the first parameter to check if request exists.
        if (!$this->checkRequestExistance()) {
            return;
        }

        // Parse the next parameter of the URL and check if request exists.
        $this->parseController();

        // Put name of the request into a full name.
        $controllerFullName = '\\App\\Http\\Controllers\\Requests\\'. $this->getControllerFullName();

        // Create the controller's instance
        $this->controllerInstance = new $controllerFullName();

        // Rest of the url are method parameters.
        $this->bindParameters();

        // call the controller's method
        $this->callControllerMethod($this->controllerInstance, $this->method, $this->parameters);
    }

    /**
     * Parse controller from the URL
     *
     * @return void
     */
    protected function parseController()
    {
        if (isset($this->url[1]) && file_exists('../app/Http/Controllers/Requests/'. $this->getControllerFullName($this->url[1]) .'.php')) {
            $this->controller = $this->url[1];
            unset($this->url[1]);
            return;
        }

        Router::redirectToError(501, Lang::get('error.failed_to_execute_request', ['request' => (isset($this->url[1]) ? $this->url[1] : '')]));
    }

    /**
     * Check if request have to be executed.
     *
     * @return bool
     */
    protected function checkRequestExistance()
    {
        if (strtolower($this->url[0]) === $this->requestIdentifier) {
            $this->requestExists = true;
            unset($this->url[0]);
            return $this->requestExists;
        }

        return $this->requestExists;
    }

    /**
     * Get attribute
     *
     * @return bool
     */
    public function requestExists()
    {
        return $this->requestExists;
    }

    /**
     * Check legitimity of access to the request.
     *
     * @return boolean
     */
    protected function isAccessLegit()
    {
        // You can access to the request with POST.
        if (!Input::exists('post')) {
            return false;
        }

        return true;
    }
}
