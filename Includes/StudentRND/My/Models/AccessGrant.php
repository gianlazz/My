<?php

namespace StudentRND\My\Models;

use \StudentRND\My\Models;

class AccessGrant extends \TinyDb\Orm
{
    public static $table_name = 'access_grants';
    public static $primary_key = 'grantID';

    public $grantID;
    public $userID;
    public $start;
    public $end;

    public static function create(Models\User $user, $start, $end)
    {
        return parent::create(array(
            'userID' => $user->userID,
            'start' => $start,
            'end' => $end
        ));
    }

    public function __get_user()
    {
        return new Models\User($this->userID);
    }
}
