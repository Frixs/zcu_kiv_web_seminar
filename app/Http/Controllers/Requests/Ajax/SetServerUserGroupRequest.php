<?php

namespace App\Http\Controllers\Requests\Ajax;

use Frixs\Http\Request;
use Frixs\Http\Input;
use Frixs\Config\Config;
use Frixs\Validation\Validate;

use Frixs\Auth\Auth;
use Frixs\Auth\Guard;

use App\Models\Server;
use App\Models\Group;
use App\Models\UserGroup;
use App\Models\User;

class SetServerUserGroupRequest extends Request
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
            'uid'       => [
                'required' => true
            ],
            'gid'       => [
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
        if (!$this->inputValidation()->passed()) {
            echo "VALIDATION ERROR!";
            return;
        }

        // Check if user has access to manage server groups.
        if (!Guard::has('server.settings.groups')) {
            return;
        }

        // Check if user exists in the server.
        if (!User::hasServerAccess(Auth::id(), Input::get('serverid')) || !User::hasServerAccess(Input::get('uid'), Input::get('serverid'))) {
            return;
        }
        
        $userCurrentGroups = Server::getUserGroups(Input::get('serverid'), Auth::id());
        $serverGroups      = Group::getAllServerGroups(true);

        // Check if user has perms to assign this group.
        if ($userCurrentGroups[0]->priority_max < $serverGroups[Input::get('gid')]['priority'] && !User::isServerOwner(Server::getServerID())) {
            return;
        }

        self::db()->beginTransaction();
        
        $query = self::db()->delete(UserGroup::getTable(), [
            'user_id', '=', Input::get('uid'),
            'AND', 'server_id', '=', Input::get('serverid'),
        ]);

        if ($query->error()) {
            self::db()->rollBack();
            return;
        }

        if (Input::get('gid') == Group::SRecruit() || Input::get('gid') == Group::SMember()) {
            $query = self::db()->insert(UserGroup::getTable(), [
                'user_id' => Input::get('uid'),
                'group_id' => Input::get('gid'),
                'server_id' => Input::get('serverid')
            ]);

            if (!$query) {
                self::db()->rollBack();
                return;
            }

        } else {
            $newGroups = [Group::SMember(), Input::get('gid')];

            for ($i = 0; $i < 2; $i++) {
                $query = self::db()->insert(UserGroup::getTable(), [
                    'user_id' => Input::get('uid'),
                    'group_id' => $newGroups[$i],
                    'server_id' => Input::get('serverid')
                ]);

                if (!$query) {
                    self::db()->rollBack();
                    return;
                }
            }
        }

        self::db()->commit();
        echo "true";
    }
}
