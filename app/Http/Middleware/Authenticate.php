<?php

namespace App\Http\Middleware;

use Frixs\Routing\Router;

class Authenticate
{
    public static function validate()
    {
        //Router::redirectToError(404);
        return true;
    }
}
