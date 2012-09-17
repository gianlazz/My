<?php

namespace StudentRND\My\Controllers;

use \StudentRND\My\Models;

class stripe extends \CuteControllers\Base\Rest
{
    public function post_index()
    {
        $body = @file_get_contents('php://input');
        $event_json = json_decode($body);
        if (isset($event_json)) {
            if ($event_json->type === 'customer.subscription.deleted') {
                $customer_id = $event_json->data->object->customer;

                $model = new Models\Mappings\UserPlan(array(
                                                  'stripe_customer_id' => $customer_id));

                $model->delete();
            }
        }
    }
}
