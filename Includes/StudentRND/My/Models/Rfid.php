<?php

namespace StudentRND\My\Models;

use \StudentRND\My\Models;

class Rfid extends \TinyDb\Orm
{
    public static $table_name = 'rfids';
    public static $primary_key = 'rfID';

    public $rfID;
    public $userID;

    public static function create($rfID, Models\User $user)
    {
        $rfID = strtoupper($rfID);

        return parent::create(array(
            'rfID' => $rfID,
            'userID' => $user->userID
        ));
    }

    public function __get_user()
    {
        return new Models\User($this->userID);
    }
}
