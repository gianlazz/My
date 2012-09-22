<?php

namespace StudentRND\My\Models\Mappings;

use StudentRND\My\Models;

class UserGroup extends \TinyDb\Orm
{
    public static $table_name = 'users_codeday_events';
    public static $primary_key = array('userID', 'eventID');

    protected $userID;
    protected $eventID;

    public static function create($userID, $eventID)
    {
        return parent::create(array(
                                 'userID' => $userID,
                                 'eventID' => $eventID));
    }

    public function __get_user()
    {
        return new Models\User($this->userID);
    }

    public function __get_event()
    {
        return new Models\CodeDay\Event($this->eventID);
    }
}
