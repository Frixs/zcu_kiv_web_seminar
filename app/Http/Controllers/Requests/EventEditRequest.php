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
use App\Models\CalendarEventSection;

class EventEditRequest extends Request
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
            'title' => [
                'required' => true,
                'min' => 3,
                'max' => 30
            ],
            'description' => [
                'max' => 1000
            ],
            'date-from' => [
                'required' => true,
                'date' => true
            ],
            'date-to' => [
                'date' => true
            ],
            'estimated-hours' => [
                'only_numbers' => true,
                'max' => 30
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
        if (!Guard::has('server.calendar_events.edit') && $authUID !== CalendarEvent::getEvent(Input::get('eventid'))->founder_user_id) {
            Router::redirectToError(500);
        }

        $query = self::db()->update(CalendarEvent::getTable(), [
            //'type' => 0,
            'title' => Input::get('title'),
            'description' => (!empty(Input::get('description')) ? Input::get('description') : ''),
            'time_from' => strtotime(Input::get('date-from')),
            'time_to' => (!empty(Input::get('date-to')) ? strtotime(Input::get('date-to')) : 0),
            'time_estimated_hours' => Input::get('estimated-hours'),
            'edited' => 1,
            'edited_time' => time()
        ], [
            CalendarEvent::getPrimaryKey(), '=', Input::get('eventid')
        ]);

        if(!$query) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }

        $this->bindMessageSuccess(Lang::get('server.event-edit.edit_success'));
        $this->goBack();
    }
}
