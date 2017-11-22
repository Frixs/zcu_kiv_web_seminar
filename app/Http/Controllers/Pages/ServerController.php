<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;

class ServerController extends Controller
{
    public function index()
    {
        $this->view('server.index');
    }

    public function join()
    {
    }

    public function sendRequest()
    {
    }
}
