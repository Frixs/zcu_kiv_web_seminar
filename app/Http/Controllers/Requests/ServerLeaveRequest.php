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

class ServerLeaveRequest extends Request
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
        $server = Server::getServer($serverID);

        // Check if user has acces to this server.
        if (!User::hasServerAccess($authUID, $serverID)) {
            Router::redirectToError(500);
        }
        
        // Check if server is joinable.
        if (User::isServerOwner($authUID)) {
            $this->bindMessageError(Lang::get('server.leave.leave_as_owner'));
            $this->goBack();
        }

        self::db()->beginTransaction();

        $query = self::db()->delete(\App\Models\UserGroup::getTable(), [
            'user_id', '=', $authUID,
            'AND', 'server_id', '=', $serverID
        ]);

        if(!$query) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }

        // Remove user from server events.
        $query = self::db()->select(\App\Models\CalendarEvent::getTable(), ['server_id', '=', $serverID], [\App\Models\CalendarEvent::getPrimaryKey()]);
        
        if ($query->error()) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }

        $events = $query->get();

        foreach ($events as $event) {
            $query = self::db()->delete(\App\Models\CalendarEventUser::getTable(), [
                'user_id', '=', $authUID,
                'AND', 'calendar_event_id', '=', $event->id
            ]);

            if(!$query) {
                self::db()->rollBack();
                Router::redirectToError(500);
            }
        }
        
        // Success.
        self::db()->commit();
        Router::redirectTo('dashboard');
    }
}
