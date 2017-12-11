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
                        'max' => 3
                    ]
                ]);
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

        // Check, user has access to the server.
        if (!User::hasServerAccess($authUID, Input::get('serverid'))) {
            Router::redirectToError(500);
        }

        // Check user permissions.
        if (!Guard::has('server.calendar_events.add_new')) {
            Router::redirectToError(500);
        }

        self::db()->beginTransaction();

        $query = self::db()->insert(CalendarEvent::getTable(), [
            'server_id' => Input::get('serverid'),
            'type' => 0,
            'title' => Input::get('title'),
            'description' => (!empty(Input::get('description')) ? Input::get('description') : ''),
            'time_from' => strtotime(Input::get('date-from')),
            'time_to' => (!empty(Input::get('date-to')) ? strtotime(Input::get('date-to')) : 0),
            'time_estimated_hours' => Input::get('estimated-hours'),
            'rating' => '',
            'recorded' => 0,
            'edited' => 0,
            'edited_time' => 0,
            'founder_user_id' => $authUID
        ]);

        if(!$query) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }

        $eventID = self::db()->lastInsertId();

        for ($i = 0; $i < count(Input::get('section-name')); $i++) {
            $query = self::db()->insert(CalendarEventSection::getTable(), [
                'calendar_event_id' => $eventID,
                'name' => Input::get('section-name')[$i],
                'is_limited' => (isset(Input::get('section-limit')[$i]) && Input::get('section-limit')[$i] > 0 ? 1 : 0),
                'limit_max' => (isset(Input::get('section-limit')[$i]) && Input::get('section-limit')[$i] > 0 ? Input::get('section-limit')[$i] : 0)
            ]);

            if(!$query) {
                self::db()->rollBack();
                Router::redirectToError(500);
            }
        }

        self::db()->commit();
        Router::redirectTo('server/server:'. Input::get('serverid') .'/event:'. $eventID);
    }
}
