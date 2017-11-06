<?php

namespace App\Http\Controllers\Requests;

use Frixs\Http\Request;
use Frixs\Http\Input;
use Frixs\Config\Config;
use Frixs\Validation\Validate;
use Frixs\Language\Lang;
use Frixs\Auth\Auth;

use App\Models\User;

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
            ],
            'terms' => [
                'required' => true
            ],
            'g-recaptcha-response' => array(
                'required' => true,
                'captcha' => Input::get('g-recaptcha-response')
            )
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
            User::register(Input::get('username'), Input::get('email'), Input::get('password'), 'NOVERIFY');
            
            $this->bindMessageSuccess(Lang::get('auth.register_success'));
            $this->goBack();
        }
        
        // Register with the email verification.
        User::register(Input::get('username'), Input::get('email'), Input::get('password'), 'VERIFY');
        
        $this->bindMessageSuccess(Lang::get('auth.register_success_verify'));
        $this->goBack();
    }
}
