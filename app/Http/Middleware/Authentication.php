<?php

namespace App\Http\Middleware;

use Frixs\Routing\Router;

class Authentication
{
    public static function validate()
    {
        //TODO
        //Router::redirectToError(404);
        return true;
    }
}
