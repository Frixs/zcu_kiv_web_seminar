<?php

class HomeController extends Controller
{
    public function index($name = '')
    {
        $user = $this->model('User');
        $user->name = "Frixs";
        echo $user->name;
    }

    public function test()
    {
        echo 'test';
    }
}