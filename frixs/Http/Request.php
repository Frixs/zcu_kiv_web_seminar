<?php

namespace Frixs\Http;

use Frixs\Routing\Router;
use Frixs\Config\Config;
use Frixs\Session\Session;

class Request
{
    /**
     * Data to flash them in views.
     *
     * @var array
     */
    protected $_data = [];

    /**
     * Redirect back to previous page. If it doest exist go bach to root
     *
     * @return void
     */
    protected function goBack()
    {
        if (Router::previousPageAddress()) {
            Router::redirectTo(Router::previousPageAddress(), true);
        }

        Router::redirectTo(Config::get('app.root'), true);
    }

    /**
     * Add value to data session.
     *
     * @param string $key
     * @param mixed  $value
     * @return void
     */
    protected function addReturnValue($key, $value)
    {
        $this->_data[$key] = $value;
        $this->unsetDataSession();
        Session::put(Config::get('session.request_data_name'), $this->_data);
    }

    /**
     * Unset session with requested data.
     *
     * @return void
     */
    protected function unsetDataSession()
    {
        Session::delete(Config::get('session.request_data_name'));
    }

    /**
     * Get data value.
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return Session::get(Config::get('session.request_data_name'))[$key];
    }
}
