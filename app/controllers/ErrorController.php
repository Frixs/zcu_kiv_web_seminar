<?php

class ErrorController extends Controller
{
    public function index($errorNumber = '501')
    {
        $eMethodFullName = 'error'. $errorNumber;

        if (method_exists($this, $eMethodFullName)) {
            $reflection = new ReflectionMethod($this, $eMethodFullName);
            if ($reflection->isPrivate()) {
                $this->$eMethodFullName();
                return;
            }
        }
        
        $this->error501();
    }

    private function error404()
    {
        $this->view('error/404');
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
