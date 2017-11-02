<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function index()
    {
        $this->view('register.index');
    }
}
