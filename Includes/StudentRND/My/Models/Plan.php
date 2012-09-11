<?php

namespace StudentRND\My\Models;

class Plan extends \TinyDb\Orm
{
    public static $table_name = 'plans';
    public static $primary_key = 'planID';

    protected $planID;
    protected $name;
    protected $stripe_id;
    protected $amount;
    protected $period;
    protected $groupID;

    protected $created_at;
    protected $modified_at;

    public static function create($name, $amount, $period, $groupID)
    {
        return parent::create(array(
                              'name' => $name,
                              'amount' => intval($amount * 100),
                              'period' => $period,
                              'groupID' => $groupID
                              ));
    }

    public function __validate_amount($new)
    {
        return intval($new * 100) == $new * 100;
    }

    public function __set_amount($new)
    {
        $this->amount = intval($new * 100);
        $this->invalidate('amount');
    }

    public function __get_amount()
    {
        return $this->amount / floatval(100);
    }

    public function __validate_period($new)
    {
        return in_array($new, array('month', '3month', '6month', 'year'));
    }

    /**
     * Gets the group associated with the Plan
     * @return Group Group associated with the plan
     */
    public function __get_group()
    {
        return new \StudentRND\My\Models\Group($this->groupID);
    }
}
