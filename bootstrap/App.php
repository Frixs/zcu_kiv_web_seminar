<?php

namespace Bootstrap;

use App\Routing\Route;

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
        $this->routeInstance = new Route();
    }
}
