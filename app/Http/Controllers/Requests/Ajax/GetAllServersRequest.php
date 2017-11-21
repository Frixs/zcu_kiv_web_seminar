<?php

namespace App\Http\Controllers\Requests\Ajax;

use Frixs\Http\Request;
use Frixs\Http\Input;
use Frixs\Config\Config;
use Frixs\Validation\Validate;
use Frixs\Language\Lang;
use Frixs\Auth\Auth;

use App\Models\User;

class GetAllServersRequest extends Request
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
            'name'       => [
                'required' => true,
                'min'      => 3
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
            echo "FAILED";
            return;
        }

        echo "HERE";
        
        /*
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
        */
    }
}
