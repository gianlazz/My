<?php

namespace StudentRND\My\Controllers;

use \StudentRND\My\Models;

class subscriptions extends \CuteControllers\Base\Rest
{
    public function before()
    {
        $this->user = \StudentRND\My\Models\User::current();
    }

    public function __get_index()
    {
        include(TEMPLATE_DIR . '/Home/subscriptions/list.php');
    }

    public function __get_details()
    {
        $plan = new Models\Plan($this->request->request('plan'));
        if ($this->user->has_plan($plan)) {
            include(TEMPLATE_DIR . '/Home/subscriptions/edit.php');
        } else {
            include(TEMPLATE_DIR . '/Home/subscriptions/new.php');
        }
    }

    public function __post_cancel()
    {
        $plan = new Models\Plan($this->request->post('plan'));
        $user_plan = new \StudentRND\My\Models\Mappings\UserPlan(array(
                                                                    'userID' => $this->user->userID,
                                                                    'planID' => $plan->planID
                                                                  ));
        try {
            $cu = \Stripe_Customer::retrieve($user_plan->stripe_customer_id);
            $cu->cancelSubscription(array('at_period_end' => true));
            $user_plan->is_cancelled = true;
            $this->redirect('/subscriptions/details?plan=' . $plan->planID);
        } catch (\Exception $ex) {
            $error = $ex->getMessage();
            include(TEMPLATE_DIR . '/Home/subscriptions/edit.php');
        }
    }

    public function __post_subscribe()
    {
        $plan = new Models\Plan($this->request->post('plan'));

        $card_name = $this->request->post('card-name');
        $card_number = $this->request->post('card-number');
        $card_cvc = $this->request->post('card-cvc');
        $card_expiry_month = $this->request->post('card-expiry-month');
        $card_expiry_year = $this->request->post('card-expiry-year');

        if (!$card_name || !$card_number || !$card_cvc || !$card_expiry_month || !$card_expiry_year) {
            $error = "All fields are required";
            include(TEMPLATE_DIR . '/Home/subscriptions/new.php');
        } else if ($this->user->has_plan($plan)) {
            $error = "You are already subscribed to that plan.";
            include(TEMPLATE_DIR . '/Home/subscriptions/new.php');
        } else {
            $user_plan = Models\Mappings\UserPlan::create($this->user->userID, $plan->planID, "--");
            try {
                $customer = \Stripe_Customer::create(array(
                  "description" => $this->user->username . "::" . $plan->name,
                  "card" => array(
                        "name" => $card_name,
                        "number" => $card_number,
                        "exp_month" => $card_expiry_month,
                        "exp_year" => $card_expiry_year,
                        "cvc" => $card_cvc
                    ),
                  "plan" => $plan->stripe_id,
                  "trial_end" => $user_plan->next_billing_date
                ));

                \Stripe_Charge::create(array(
                    "amount" => round($user_plan->prorated_amount * 100),
                    "currency" => "usd",
                    "customer" => $customer->id,
                    "description" => "Charge for test@example.com")
                );

                $user_plan->stripe_customer_id = $customer->id;
                $user_plan->update();

                $this->redirect('/subscriptions');
            } catch (\Exception $ex) {
                $user_plan->delete();
                $error = $ex->getMessage();
                include(TEMPLATE_DIR . '/Home/subscriptions/new.php');
            }
        }
    }
}
