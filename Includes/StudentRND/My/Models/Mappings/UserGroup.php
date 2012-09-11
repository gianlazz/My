<?php

namespace StudentRND\My\Models\Mappings;

use StudentRND\My\Models;

class UserGroup extends \TinyDb\Orm
{
    public static $table_name = 'users_groups';
    public static $primary_key = array('userID', 'groupID');

    protected $userID;
    protected $groupID;

    public static function create($userID, $groupID)
    {
        return parent::create(array(
                                 'userID' => $userID,
                                 'groupID' => $groupID));
    }

    public function __get_user()
    {
        return new Models\User($this->userID);
    }

    public function __get_group()
    {
        return new Models\Group($this->groupID);
    }
}
