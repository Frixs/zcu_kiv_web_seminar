<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;

class ServerController extends Controller
{
    public function index()
    {
        $thisserver = \App\Models\Server::getServer(\App\Models\Server::getServerID());

        $this->view('server.index', ['thisserver' => $thisserver]);
    }

    public function join()
    {
        $thisserver = \App\Models\Server::getServer(\App\Models\Server::getServerID());

        $this->view('server.join', ['thisserver' => $thisserver]);
    }

    public function sendRequest()
    {
        $thisserver = \App\Models\Server::getServer(\App\Models\Server::getServerID());

        $this->view('server.join', ['thisserver' => $thisserver]);
    }

    public function leave()
    {
        $thisserver = \App\Models\Server::getServer(\App\Models\Server::getServerID());

        $this->view('server.leave', ['thisserver' => $thisserver]);
    }

    public function create()
    {
        $this->view('server.create');
    }

    public function settings()
    {
        $thisserver = \App\Models\Server::getServer(\App\Models\Server::getServerID());

        $this->view('server.settings', ['thisserver' => $thisserver]);
    }
}
