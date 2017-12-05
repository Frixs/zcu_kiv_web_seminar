<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Frixs\Config\Config;
use Frixs\Routing\Router;
use Frixs\Session\Session;

class ErrorController extends Controller
{
    private $recentErrorMessage = "";

    public function index($errorCode = '501')
    {
        if (!Session::exists('error_message')) {
            Router::redirectTo(Config::get('app.root_rel'), true);
        }

        $this->recentErrorMessage = Session::flash('error_message');
        if ($this->recentErrorMessage == 'null') {
            $this->recentErrorMessage = "";
        }

        $eMethodFullName = 'error'. $errorCode;

        // validate paramater error code
        if (method_exists($this, $eMethodFullName)) {
            $reflection = new \ReflectionMethod($this, $eMethodFullName);
            if ($reflection->isPrivate()) {
                $this->$eMethodFullName();
                return;
            }
        }
        
        // default error
        $this->error500();
    }

    private function error001()
    {
        $this->view('error.001', ['error_message' => $this->recentErrorMessage]);
    }

    private function error401()
    {
        $this->view('error.401', ['error_message' => $this->recentErrorMessage]);
    }

    private function error404()
    {
        $this->view('error.404', ['error_message' => $this->recentErrorMessage]);
    }

    private function error500()
    {
        $this->view('error.500', ['error_message' => $this->recentErrorMessage]);
    }

    private function error501()
    {
        $this->view('error.501', ['error_message' => $this->recentErrorMessage]);
    }

    private function error503()
    {
        $this->view('error.503', ['error_message' => $this->recentErrorMessage]);
    }
}
