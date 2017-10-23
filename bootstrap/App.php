<?php

namespace Bootstrap;

use Frixs\Routing\Route;

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
    }
}
