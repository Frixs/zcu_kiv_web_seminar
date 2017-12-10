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

class EventCreateRequest extends Request
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
            ],
            'section-name' => [
                'required' => true
            ],
            'section-limit' => [
                'required' => true
            ],
        ]);

        // Validate sections.
        if ($validation->passed()) {
            $sectionNames  = Input::get('section-name');
            $sectionLimits = Input::get('section-limit');
            
            for ($i = 0; $i < count($sectionNames); $i++) {
                $validation = $validation->check($sectionNames, [
                    (''.$i) => [
                        'required' => true,
                        'min' => 1,
                        'max' => 15
                    ]
                ]);
                
                $validation = $validation->check($sectionLimits, [
                    (''.$i) => [
                        'only_numbers' => true,
                        'min' => 1,
                        'max' => 4
                    ]
                ]);

                if (!$validation->passed()) {
                    break;
                }
            }
        }

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
/*
        // Check, user has access to the server.
        if (!User::hasServerAccess($authUID, Input::get('serverid'))) {
            Router::redirectToError(500);
        }
        // Check event is assigned to the server.
        if (!Server::hasEvent(Input::get('serverid'), Input::get('eventid'))) {
            Router::redirectToError(500);
        }
        // Check user permissions.
        if (!Guard::has('server.calendar_events.delete') && $authUID !== CalendarEvent::getEvent(Input::get('eventid'))->founder) {
            Router::redirectToError(500);
        }

        // Delete the event.
        $query = self::db()->delete(CalendarEvent::getTable(), [
            CalendarEvent::getPrimaryKey(), '=', Input::get('eventid')
        ]);
        
        if(!$query) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }
*/
        $this->goBack();
    }
}
