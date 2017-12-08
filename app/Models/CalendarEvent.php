<?php

namespace App\Models;

use Frixs\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    // Use Model parameters.
    use \Frixs\Database\Eloquent\ModelParameters;

    /**
     * Create an instance of the model.
     */
    public function __construct($attributes = [])
    {
        $this->initModel();

        // You can override attributes from the Model class via SETs.
        //static::setTable('TABLE_NAME');
    }

    /**
     * Get Event database object.
     *
     * @param integer $eid
     * @return object
     */
    public static function getEvent($eid) {
        if (!$eid) {
            return null;
        }

        $query = self::db()->query(
            "SELECT e.*,
                u.username AS founder_name,
                (
                    SELECT COUNT(eu.user_id)
                    FROM ". CalendarEventUser::getTable() ." AS eu
                    WHERE eu.calendar_event_id = e.". self::getPrimaryKey() ."
                ) AS user_count,
                (
                    SELECT COUNT(eu2.user_id)
                    FROM ". CalendarEventUser::getTable() ." AS eu2
                    WHERE eu2.user_id = ". \Frixs\Auth\Auth::id() ."
                ) AS participation
            FROM ". self::getTable() ." AS e
            INNER JOIN ". User::getTable() ." as u
                    ON u.". User::getPrimaryKey() ." = e.founder_user_id
            WHERE e.". self::getPrimaryKey() ." = ?"
        , [
            $eid
        ]);

        if ($query->error()) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }

        if ($query->count()) {
            return $query->getFirst();
        }

        return null;
    }

    /**
     * Get Server's events.
     *
     * @param integer $uid  User ID.
     * @return object
     */
    public static function getServerEvents($serverid) {
        if (!$serverid) {
            return null;
        }

        $query = self::db()->query(
            "SELECT e.*,
                u.username AS founder_name,
                (
                    SELECT COUNT(eu.user_id)
                    FROM ". CalendarEventUser::getTable() ." AS eu
                    WHERE eu.calendar_event_id = e.". self::getPrimaryKey() ."
                ) AS user_count,
                (
                    SELECT COUNT(eu2.user_id)
                    FROM ". CalendarEventUser::getTable() ." AS eu2
                    WHERE eu2.user_id = ". \Frixs\Auth\Auth::id() ."
                ) AS participation
            FROM ". self::getTable() ." AS e
            INNER JOIN ". User::getTable() ." as u
                    ON u.". User::getPrimaryKey() ." = e.founder_user_id
            WHERE e.server_id = ?
            ORDER BY e.time_from ASC"
        , [
            $serverid
        ]);

        if ($query->error()) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }

        return $query->get();
    }
}
