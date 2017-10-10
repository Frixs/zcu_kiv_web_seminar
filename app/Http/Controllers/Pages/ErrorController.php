<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;

class ErrorController extends Controller
{
    public function index($errorCode = '501')
    {
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

    private function error404()
    {
        $this->view('error/404');
    }

    private function error500()
    {
        $this->view('error/500');
    }

    private function error501()
    {
        $this->view('error/501');
    }

    private function error503()
    {
        $this->view('error/503');
    }
}
