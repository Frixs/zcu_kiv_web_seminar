<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function index()
    {
        $this->view('login.index', ['variable_name' => 'SOME VARIABLE']);
    }
}
