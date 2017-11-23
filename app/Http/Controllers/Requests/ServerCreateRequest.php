<?php

namespace App\Http\Controllers\Requests;

use Frixs\Http\Request;
use Frixs\Http\Input;
use Frixs\Config\Config;
use Frixs\Validation\Validate;
use Frixs\Language\Lang;
use Frixs\Auth\Auth;
use App\Http\Kernel;

class ServerCreateRequest extends Request
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
            'name' => [
                'required' => true,
                'min'      => 3,
                'max'      => 50
            ],
            'access-type' => [
                'required' => true
            ],
            'background-placeholder' => [
                'file_size_max' => Config::get('fileupload.filesize_max.server_background_placeholder'),
                'file_type_allowed' => 'jpg',
                'file_img_size_max' => '650|75',
                'file_img_size_min' => '350|75'
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
    }
}
