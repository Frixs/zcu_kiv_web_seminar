<?php

namespace App\Http\Controllers\Requests;

use Frixs\Http\Request;
use Frixs\Http\Input;
use Frixs\Config\Config;
use Frixs\Validation\Validate;
use Frixs\Validation\Escape;

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

        $validation = (new Validate())->check(Input::getAll('post'), [
            'email'       => [
                'required' => true,
                'max'      => 150
            ],
            'password'       => [
                'required' => true,
                'min'      => Config::get('auth.password_min_len'),
                'color'      => true,
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
        
        echo "passed";
        $this->goBack();
    }
}
