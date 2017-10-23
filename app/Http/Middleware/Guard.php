<?php

namespace App\Http\Middleware;

use Frixs\Database\Connection as DB;
use Frixs\Routing\Router;
use Frixs\Auth\Auth;

use App\Models\UserGroup;
use App\Models\Group;

class Guard
{
    public static function validate($parameters = [])
    {
        for ($i = 0; $i < count($parameters); $i++) {
            $query = DB::getInstance()->query("
                SELECT g.server_group
                FROM ". UserGroup::getTable() ." AS ug
                INNER JOIN ". Group::getTable() ." AS g
                    ON ug.group_id = ". Group::getPrimaryKey() ."
                WHERE ug.user_id = ? AND ug.group_id = ? AND (g.server_group = ? OR (g.server_group = ? AND ug.server_id = ?))
            ", [
                Auth::id(),
                $parameters[$i],
                0,
                1,
                \App\Models\Server::getServer()
            ]);

            if ($query->error()) {
                Router::redirectToError(500);
            }
    
            if (!$query->count()) {
                Router::redirectToError(401);
            }
        }
        
        return true;
    }
}
