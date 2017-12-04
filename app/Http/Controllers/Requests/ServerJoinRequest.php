<?php

namespace App\Http\Controllers\Requests;

use Frixs\Http\Request;
use Frixs\Http\Input;
use Frixs\Config\Config;
use Frixs\Validation\Validate;
use Frixs\Language\Lang;
use Frixs\Routing\Router;
use Frixs\Auth\Auth;

use App\Models\User;
use App\Models\Server;

class ServerJoinRequest extends Request
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
        $serverID = null;
        $authUID = Auth::id();
        
        if (!$this->inputValidation()->passed()) {
            $this->goBack();
        }
        
        $serverID = Input::get('serverid');
        $server = Server::getServeR($serverID);

        // Check if server is joinable.
        if ($server->access_type !== 0 // Public room
            OR $server->access_type !== 1 // Protected room
        ) {
            $this->bindMessageError(Lang::get('error.server_is_not_joinable'));
            $this->goBack();
        }

        // Check if user already has acces to this server.
        if (!User::hasServerAccess($authUID, $serverID)) {
            // Add group recruit to the user.
            $query = self::db()->insert(\App\Models\UserGroup::getTable(), [
                'user_id' => $authUID,
                'group_id' => \App\Models\Group::SRecruit(),
                'server_id' => $serverID
            ]);
            
            if(!$query) {
                self::db()->rollBack();
                Router::redirectToError(500);
            }
        }
        
        Router::redirectTo('server/server:'. $serverID);
    }
}
