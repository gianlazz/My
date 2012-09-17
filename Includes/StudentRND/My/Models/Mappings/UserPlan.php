<?php

namespace StudentRND\My\Models\Mappings;

use StudentRND\My\Models;

class UserPlan extends \TinyDb\Orm
{
    public static $table_name = 'users_plans';
    public static $primary_key = array('userID', 'planID');

    protected $userID;
    protected $planID;
    protected $stripe_customer_id;
    protected $is_cancelled;

    protected $created_at;
    protected $modified_at;

    public static function create($userID, $planID, $stripe_customer_id)
    {
        return parent::create(array(
                                 'userID' => $userID,
                                 'planID' => $planID,
                                 'stripe_customer_id' => $stripe_customer_id));
    }

    public function __get_next_billing_date()
    {
        $cur_month = date('n');
        $cur_year  = date('Y');

        if ($this->plan->period == 'month') {
            if ($cur_month == 12) {
                $next_billing_date = mktime(0, 0, 0, 0, 0, $cur_year+1);
            } else {
                $next_billing_date = mktime(0, 0, 0, $cur_month+1, 1);
            }
        } else if($this->plan->period == 'year') {
            $next_billing_date = mktime(0, 0, 0, 0, 0, $cur_year+1);
        }

        return $next_billing_date;
    }

    public function __get_current_billing_interval()
    {
        if ($this->plan->period == 'month') {
            return cal_days_in_month(CAL_GREGORIAN, date('n'), date('Y'));
        } else if ($this->plan->period == 'year') {
            if (date('L')) {
                return 366;
            } else {
                return 365;
            }
        }
    }

    public function __get_prorated_amount()
    {
        return round($this->plan->amount * ($this->days_till_billing_date / $this->current_billing_interval), 2);
    }

    public function __get_days_till_billing_date()
    {
        return floor(($this->next_billing_date - mktime()) / (24 * 3600));
    }

    public function __get_user()
    {
        return new Models\User($this->userID);
    }

    public function __get_plan()
    {
        return new Models\Plan($this->planID);
    }
}
