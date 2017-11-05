<?php

namespace App\Http\Controllers\Requests;

use Frixs\Http\Request;
use Frixs\Http\Input;
use Frixs\Config\Config;
use Frixs\Validation\Validate;
use Frixs\Language\Lang;
use Frixs\Auth\Auth;

class RegisterRequest extends Request
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
            'username' => [
                'required' => true,
                'min'      => 2,
                'max'      => 20,
                'unique'   => \App\Models\User::getTable()
            ],
            'email' => [
                'required' => true,
                'max'      => 150,
                'unique'   => \App\Models\User::getTable()
            ],
            'password' => [
                'required' => true,
                'min'      => Config::get('auth.password_min_len'),
                'max'      => Config::get('auth.password_max_len'),
                'matches'  => 'password-check'
            ],
            'password-check' => [
                'required' => true
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
        
        if (!Config::get('auth.register_email_verify')) {
            // Register without verification.
            $this->bindMessageSuccess(Lang::get('auth.register_success_verify'));
            $this->goBack();
        }

        // TODO: Register with the email verification.
        $this->bindMessageSuccess(Lang::get('auth.register_success'));
        $this->goBack();
    }
}
