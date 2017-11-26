<?php

namespace App\Http\Controllers\Requests;

use Frixs\Http\Request;
use Frixs\Http\Input;
use Frixs\Config\Config;
use Frixs\Validation\Validate;
use Frixs\Language\Lang;
use Frixs\Routing\Router;
use Frixs\Auth\Auth;
use Frixs\Auth\Guard;

class ServerEditRequest extends Request
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
            'serverid' => [
            ],
            'name' => [
                'required' => true,
                'min'      => 3,
                'max'      => 50,
                'letters_numbers_undersc_space' => true
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
            'g-recaptcha-response' => [
                'required' => true,
                'captcha' => Input::get('g-recaptcha-response')
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
        $hasBackgroundPlaceholder;
        $serverID = null;
        $authUID = Auth::id();
        $updateArr = null;
        
        if (!$this->inputValidation()->passed()) {
            $this->goBack();
        }

        $serverID = Input::get('serverid');
        $hasBackgroundPlaceholder = Input::getFileData('background-placeholder')['error'] === 0 ? 1 : 0;

        self::db()->beginTransaction();

        if (!Guard::has('server.settings.basics')) {
            Router::redirectToError(500);
        }

        $updateArr = [
            'name' => Input::get('name'),
            'access_type' => Input::get('access-type') === 'public' ? 0 : (Input::get('access-type') === 'protected' ? 1 : 2),
            'has_background_placeholder' => $hasBackgroundPlaceholder,
            'edited_at' => time()
        ];

        if ($hasBackgroundPlaceholder) {
            $updateArr['has_background_placeholder'] = $hasBackgroundPlaceholder;
        }

        $query = self::db()->update(\App\Models\Server::getTable(), $updateArr, [
            'id', '=', $serverID
        ]);

        if (!$query) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }

        if ($hasBackgroundPlaceholder) {
            $filename = $serverID ."_background_placeholder.". Input::getFileData('background-placeholder', 'extension');
            if (!move_uploaded_file(Input::getFileData('background-placeholder', 'tempname'), Config::get('app.root_server_uploads_rel') .'/'. $filename)) {
                self::db()->rollBack();
                Router::redirectToError(500);
            }
        }

        $this->bindMessageSuccess(Lang::get('server.settings.success_edit'));
        self::db()->commit();
        $this->goBack();
    }
}
