<?php

namespace App\Http\Middleware;

use Frixs\Routing\Router;
use Frixs\Auth\Auth;

class NotAuthenticate
{
    public static function validate($parameters = [])
    {
        if (!Auth::check()) {
            return true;
        }
        
        Router::redirectTo('home');
    }
}
