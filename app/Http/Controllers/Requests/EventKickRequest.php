<?php

namespace App\Http\Controllers\Requests;

use Frixs\Http\Request;
use Frixs\Http\Input;
use Frixs\Config\Config;
use Frixs\Validation\Validate;
use Frixs\Language\Lang;
use Frixs\Auth\Auth;
use Frixs\Routing\Router;
use Frixs\Auth\Guard;

use App\Models\User;
use App\Models\Server;
use App\Models\CalendarEvent;
use App\Models\CalendarEventUser;

class EventKickRequest extends Request
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
            'eventid' => [
            ],
            'userid' => [
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

        $authUID = Auth::id();
        
        // Check, user has access to the server.
        if (!User::hasServerAccess($authUID, Input::get('serverid'))) {
            Router::redirectToError(500);
        }
        // Check event is assigned to the server.
        if (!Server::hasEvent(Input::get('serverid'), Input::get('eventid'))) {
            Router::redirectToError(500);
        }

        // Check user permissions.
        if (!Guard::has('server.calendar_events.kick')
            && $authUID !== CalendarEvent::getEvent(Input::get('eventid'))->founder_user_id
            ) {
            Router::redirectToError(500);
        }

        $query = self::db()->delete(CalendarEventUser::getTable(), [
            'user_id', '=', Input::get('userid'),
            'AND', 'calendar_event_id', '=', Input::get('eventid')
        ]);

        if (!$query) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }

        $this->goBack();
    }
}
