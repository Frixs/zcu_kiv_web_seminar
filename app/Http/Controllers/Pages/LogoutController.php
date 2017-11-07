<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;

use Frixs\Auth\Auth;
use Frixs\Routing\Router;

class LogoutController extends Controller
{
    public function index()
    {
        Auth::logout(Auth::id());
        Router::redirectTo('home');
    }
}
