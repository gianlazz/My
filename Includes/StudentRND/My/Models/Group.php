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
    protected $type;

    protected $created_at;
    protected $modified_at;

    public static function create($name, $description, $type, $has_profile_badge)
    {
        return parent::create(array(
                       'name' => $name,
                       'description' => $description,
                       'type' => $type,
                       'has_profile_badge' => $has_profile_badge));
    }

    public function __get_users()
    {
        $collection = new \TinyDb\Collection('\StudentRND\My\Models\Mappings\UserGroup', \TinyDb\Sql::create()
                                             ->select('*')
                                             ->from(Mappings\UserGroup::$table_name)
                                             ->where('groupID = ?', $this->groupID));

        return $collection->each(function($mapping)
        {
            return $mapping->user;
        });
    }
}
