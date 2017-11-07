<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Frixs\Routing\Router;

class HomeController extends Controller
{
    public function index($name = '')
    {
        Router::redirectTo('dashboard');
        $this->view('home.index', ['name' => 'Frixs']);
    }
}
