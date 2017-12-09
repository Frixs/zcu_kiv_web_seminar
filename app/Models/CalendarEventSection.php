<?php

namespace App\Models;

use Frixs\Database\Eloquent\Model;

class CalendarEventSection extends Model
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
     * Get Event sections.
     *
     * @param integer $eid
     * @return object
     */
    public static function getEventSections($eid) {
        if (!$eid) {
            return null;
        }

        $query = self::db()->query(
            "SELECT es.*,
                (
                    SELECT COUNT(eu.user_id)
                    FROM ". CalendarEventUser::getTable() ." AS eu
                    WHERE eu.calendar_event_section_id = es.". self::getPrimaryKey() ."
                ) AS user_count
            FROM ". self::getTable() ." AS es
            WHERE es.calendar_event_id = ?"
        , [
            $eid
        ]);

        if ($query->error()) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }

        return $query->get();
    }

    /**
     * Get Event section users.
     *
     * @param integer $sectionid
     * @return object
     */
    public static function getUsers($sectionid) {
        if (!$sectionid) {
            return null;
        }

        $query = self::db()->query(
            "SELECT es.*,
                esu.*,
                u.username
            FROM ". self::getTable() ." AS es
            INNER JOIN ". CalendarEventUser::getTable() ." AS esu
                ON esu.calendar_event_section_id = es.". self::getPrimaryKey() ."
            INNER JOIN ". User::getTable() ." AS u
                ON u.". User::getPrimaryKey() ." = esu.user_id
            WHERE es.". self::getPrimaryKey() ." = ?
            ORDER BY esu.joined_time ASC"
        , [
            $sectionid
        ]);

        if ($query->error()) {
            self::db()->rollBack();
            Router::redirectToError(500);
        }

        return $query->get();
    }
}
