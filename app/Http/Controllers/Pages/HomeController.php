<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index($name = '')
    {
        $user = $this->model('User');

        echo \Frixs\Auth\Auth::verify('pwd', ['username_clean' => 'anonymous']);
        //\Frixs\Auth\Auth::login(1, false);

        $this->view('home.index', ['name' => 'Frixs']);
    }
}
