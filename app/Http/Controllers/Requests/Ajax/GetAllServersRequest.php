<?php

namespace App\Http\Controllers\Requests\Ajax;

use Frixs\Http\Request;
use Frixs\Http\Input;
use Frixs\Config\Config;
use Frixs\Validation\Validate;

use App\Models\User;

class GetAllServersRequest extends Request
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
            'name'       => [
                'required' => true,
                'min'      => 3
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

        $query = self::db()->query(
            "SELECT s.id,
                s.name,
                s.access_type,
                s.has_background_box,
                (
                    SELECT COUNT(DISTINCT ug.user_id)
                    FROM ". \App\Models\UserGroup::getTable() ." AS ug
                    WHERE ug.server_id = s.id
                ) AS user_count
            FROM ". \App\Models\Server::getTable() ." AS s
            WHERE s.name LIKE ?
            ORDER BY user_count DESC, s.name ASC
            LIMIT 10"
        , [
            '%'. Input::get('name') .'%'
        ]);

        if ($query->error()) {
            self::db()->rollBack();
            return;
        }
        
        echo json_encode($query->get());
    }
}
