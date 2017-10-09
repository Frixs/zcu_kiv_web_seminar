<?php

namespace App\Http\Controllers;

/**
 * The main Controller
 */
class Controller
{
    /**
     * Call the model, f.e. 'User'
     *
     * @param string $model     f.e. 'User'
     * @return Object           created object model
     */
    public function model($model)
    {
        $model = '\\App\\Models\\'. $model;
        return new $model();
    }

    /**
     * Call the view
     *
     * @param string $view      path to the view file from the view folder root, f.e. 'home/index'
     * @param array $data       array of variables which can be use in your view
     * @return void
     */
    public function view($view, $data = [])
    {
        $path = '../resources/views/'. $view .'.phtml';

        if (!file_exists($path)) {
            Redirect::to(404);
        }

        require_once $path;
    }
}
