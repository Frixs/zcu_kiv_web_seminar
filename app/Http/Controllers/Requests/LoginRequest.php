<?php

namespace App\Http\Controllers\Requests;

use Frixs\Http\Request;
use Frixs\Http\Input;
use Frixs\Config\Config;
use Frixs\Validation\Validate;
use Frixs\Validation\Escape;

class LoginRequest extends Request
{
    public function process()
    {
        print_r(Input::getAll('post'));
        $validation = (new Validate())->check(Input::getAll('post'), [
            'email'       => [
                'required' => true,
                'max'      => 150
            ],
            'password'       => [
                'required' => true,
                'min'      => Config::get('auth.password_min_len'),
                'max'      => Config::get('auth.password_max_len')
            ]
        ]);

        if ($validation->passed()) {
            echo "passed";
        } else {
            print_r($validation->errors());
        }

        $this->goBack();
    }
}
