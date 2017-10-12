<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index($name = '')
    {
        $user = $this->model('User');
        
        $this->view('home/index', ['name' => 'noname']);
    }

    public function test()
    {
        $this->view('home/test');
    }
}
