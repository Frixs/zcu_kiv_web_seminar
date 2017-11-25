<?php

namespace Frixs\Routing;

use App\Http\Kernel;
use Frixs\Routing\Router;
use Frixs\Language\Lang;
use Frixs\Http\Input;
use Frixs\Http\Token;

class RouteRequest extends Route
{
    /**
     * Singleton
     *
     * @var object
     */
    private static $_instance = null;

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
    protected $requestIdentifier = '__request';

    /**
     * Bool of request existance.
     *
     * @var boolean
     */
    protected $requestExists = false;

    /**
     * Bool of request ajax presence.
     *
     * @var boolean
     */
    protected $isAjaxBased = false;

    /**
     * Ajax identifier.
     *
     * @var string
     */
    protected $ajaxRequestIdentifier = '__ajax';

    /**
     * Create a new RouteRequest instance
     */
    private function __construct()
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

        // Validate form TOKEN.
        // Token input from correct form must have the same name as request name. - This is true if there is custom input token name not default.
        if (!Token::validation() && !$this->isAjaxBased) {
            Router::redirectToError(500, Lang::get('error.unauthorized_token'));
        }

        // Put name of the request into a full name.
        $controllerFullName = '\\App\\Http\\Controllers\\Requests\\'. ($this->isAjaxBased ? "Ajax\\" : "") . $this->getControllerFullName();

        // Create the controller's instance
        $this->controllerInstance = new $controllerFullName();

        // Add inputs to return request values.
        $this->controllerInstance->addInputs();

        // Add input error messages to return request values.
        $this->controllerInstance->addInputErrors($this->controllerInstance->inputValidation());

        // Rest of the url are method parameters.
        $this->bindParameters();

        // Run the main validation tests (middleware).
        if (Kernel::run($this->controller, $this->method, $this->parameters, true)) {
            // Call the controller's method.
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
            self::$_instance = new RouteRequest();
        }
        return self::$_instance;
    }

    /**
     * Parse controller from the URL.
     *
     * @return void
     */
    protected function parseController()
    {
        if (isset($this->url[1]) && file_exists('../app/Http/Controllers/Requests/'. $this->getControllerFullName($this->url[1]) .'.php')) {
            $this->controller = $this->url[1];
            unset($this->url[1]);
            return;
        // If Request does not exist, check if request exists in AJAX content.
        } else if (isset($this->url[1]) && $this->url[1] === $this->ajaxRequestIdentifier) {
            $this->parseAjaxController();
            return;
        }

        Router::redirectToError(501, Lang::get('error.failed_to_execute_request', ['request' => (isset($this->url[1]) ? $this->url[1] : '')]));
    }
    
    /**
     * Prase controller assigned to AJAX request.
     *
     * @return void
     */
    private function parseAjaxController() {
        unset($this->url[1]);
        
        if (isset($this->url[2]) && file_exists('../app/Http/Controllers/Requests/Ajax/'. $this->getControllerFullName($this->url[2]) .'.php')) {
            $this->controller = $this->url[2];
            unset($this->url[2]);
            $this->isAjaxBased = true;
            return;
        }

        Router::redirectToError(501, Lang::get('error.failed_to_execute_request', ['request' => (isset($this->url[2]) ? $this->url[2] : '')]));
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
        if (Input::exists('post') && Router::previousPageAddress()) {
            return true;
        }

        return false;
    }
}
