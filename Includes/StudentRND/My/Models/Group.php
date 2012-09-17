<?php

namespace StudentRND\My\Models;

class Group extends \TinyDb\Orm
{
    public static $table_name = 'groups';
    public static $primary_key = 'groupID';

    protected $groupID;
    protected $name;
    protected $description;
    protected $has_profile_badge;

    protected $created_at;
    protected $modified_at;

    public static function create($name, $description, $has_profile_badge)
    {
        return parent::create(array(
                       'name' => $name,
                       'description' => $description,
                       'has_profile_badge' => $has_profile_badge));
    }

    public function __get_users()
    {
        $collection = new \TinyDb\Collection('\StudentRND\My\Models\Mappings\UserGroup', \TinyDb\Sql::create()
                                             ->select('*')
                                             ->from(Mappings\UserGroup::$table_name)
                                             ->where('userID = ?', $this->userID));

        return $collection->each(function($mapping)
        {
            return $mapping->user;
        });
    }
}
