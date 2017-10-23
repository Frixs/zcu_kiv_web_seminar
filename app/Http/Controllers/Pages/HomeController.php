<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index($name = '')
    {
        $this->view('home.index', ['name' => 'Frixs']);
    }
}
