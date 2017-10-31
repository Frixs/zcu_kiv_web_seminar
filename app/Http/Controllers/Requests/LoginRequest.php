<?php

namespace App\Http\Controllers\Requests;

use Frixs\Http\Request;
use Frixs\Http\Input;
use Frixs\Config\Config;
use Frixs\Validation\Validate;
use Frixs\Language\Lang;
use Frixs\Auth\Auth;

class LoginRequest extends Request
{
    /**
     * Validate inputs.
     *
     * @return object
     */
    public function inputValidation()
    {
        if ($this->_validation) {
            return $this->_validation;
        }

        $validation = (new Validate())->check(Input::all('post'), [
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

        return $this->_validation = $validation;
    }

    /**
     * Main process method.
     *
     * @return void
     */
    public function process()
    {
        if (!$this->inputValidation()->passed()) {
            $this->goBack();
        }
        
        if (!Auth::login(Input::get('email'), Input::get('password'), Input::get('remember') ? true : false)) {
            $this->bindMessageError(Lang::get('auth.failed'));
        }

        $this->goBack();
    }
}
