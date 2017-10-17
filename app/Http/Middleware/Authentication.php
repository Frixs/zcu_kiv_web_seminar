<?php

namespace App\Http\Middleware;

use Frixs\Routing\Router;
use Frixs\Auth\Auth;

class Authentication
{
    public static function validate()
    {
        if (Auth::check()) {
            return true;
        }
        
        Router::redirectTo('login');
    }
}
