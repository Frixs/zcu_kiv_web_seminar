<?php

namespace App\Http\Controllers\Requests\Ajax;

use Frixs\Http\Request;
use Frixs\Http\Input;
use Frixs\Config\Config;
use Frixs\Validation\Validate;

use Frixs\Language\Lang;

class GetLangRequest extends Request
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
            'string'       => [
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
        $langPrefix = '$$$';

        if (!$this->inputValidation()->passed()) {
            echo $langPrefix . "PLACEHOLDER";
            return;
        }

        echo $langPrefix . Lang::get(Input::get('string'));
    }
}
