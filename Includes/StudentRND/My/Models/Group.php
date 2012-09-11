<?php

namespace StudentRND\My\Models;

class Group extends \TinyDb\Orm
{
    public static $table_name = 'groups';
    public static $primary_key = 'groupID';

    protected $groupID;
    protected $name;
    protected $description;
    protected $has_group_page;
    protected $has_profile_badge;

    protected $created_at;
    protected $modified_at;

    public static function create($name, $description, $has_group_page, $has_profile_badge)
    {
        return parent::create(array(
                       'name' => $name,
                       'description' => $description,
                       'has_group_page' => $has_group_page,
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

    /**
     * Gets the plans associated with the group
     * @return \TinyDb\Collection Collection of associated Plans
     */
    public function __get_associated_plans()
    {
        return new \TinyDb\Collection('\StudentRND\My\Models\Plan', \TinyDb\Sql::create()
                                                   ->select('*')
                                                   ->from(\StudentRND\My\Models\Plan::$table_name)
                                                   ->where('groupID = ?', $this->groupID));
    }

    /**
     * Checks if the group is associated with a plan
     * @return bool True if the group is associated with a plan, false otherwise
     */
    public function __get_is_associated_with_plan()
    {;
        return count($this->associated_plans) > 0;
    }
}
