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
    protected function inputValidation()
    {
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

        return $validation;
    }

    /**
     * Main process method.
     *
     * @return void
     */
    public function process()
    {
        if (!$this->inputValidation()->passed()) {
            print_r($this->inputValidation()->errors());
            $this->goBack();
        }
        
        echo "passed";
        $this->goBack();
    }
}
