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

class EventJoinRequest extends Request
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
            'sectionid' => [
            ],
            'notice' => [
                'min' => 2,
                'max' => 255
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

        $query = self::db()->select(CalendarEventUser::getTable(), [
            'user_id', '=', $authUID,
            'AND', 'calendar_event_id', '=', Input::get('eventid')
        ], ['user_id']);

        if ($query->error()) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }

        $isMemberOfTheEvent = $query->count() > 0 ? true : false;

        // Check user permissions.
        if (!Guard::has('server.calendar_events.join')
            || $isMemberOfTheEvent
            || CalendarEvent::getEvent(Input::get('eventid'))->time_from + Config::get('event.join_time_after_start') <= time()
            ) {
            Router::redirectToError(500);
        }

        $query = self::db()->insert(CalendarEventUser::getTable(), [
            'calendar_event_section_id' => Input::get('sectionid'),
            'calendar_event_id' => Input::get('eventid'),
            'user_id' => $authUID,
            'notice' => (!empty(Input::get('notice')) ? Input::get('notice') : ''),
            'participation' => 1,
            'joined_time' => time()
        ]);

        if (!$query) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }

        $this->goBack();
    }
}
