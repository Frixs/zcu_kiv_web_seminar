<?php

namespace Bootstrap;

use Frixs\Routing\Route;

use Frixs\Http\Request;

/**
 * Initialize, loader
 */
class App
{
    /**
     * Route instance
     *
     * @var Route
     */
    private $routeInstance;

    /**
     * The main contructor
     */
    public function __construct()
    {
        $this->routeInstance = Route::getInstance();

        $this->procedureAfter();
    }

    /**
     * Procedure some code after loading the complete page.
     *
     * @return void
     */
    protected function procedureAfter()
    {
        // Unset request session if exists.
        (new Request())->unsetDataSession();
    }
}
