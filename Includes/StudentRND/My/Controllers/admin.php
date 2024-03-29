<?php

namespace StudentRND\My\Controllers;

use \StudentRND\My\Models;

class admin extends \CuteControllers\Base\Rest
{
    public function before()
    {
        $this->user = \StudentRND\My\Models\User::current();
        if (!$this->user->is_admin) {
            throw new \CuteControllers\HttpError(403);
        }
    }

    public function __get_phpinfo()
    {
        phpinfo();
        exit;
    }

    public function __get_err($code)
    {
        throw new \CuteControllers\HttpError($code);
    }

    public function __get_index()
    {
        include(TEMPLATE_DIR . '/Home/admin.php');
    }

    public function __post_user_lookup()
    {
        $rfid = $this->request->post('rfID');

        if (isset($rfid)) {
            $rfid = new Models\Rfid($rfid);
            $this->redirect('/user/?username=' . $rfid->user->username);
        }
    }

    public function __post_create_user()
    {
        $email = $this->request->post('email');
        $first_name = $this->request->post('first_name');
        $last_name = $this->request->post('last_name');
        $studentrnd_email_enabled = $this->request->post('studentrnd_email_enabled') ? TRUE : FALSE;
        $is_admin = $this->request->post('is_admin') ? TRUE : FALSE;
        $groups = $this->request->post('groups');

        if (!$email || !$first_name || !$last_name) {
            $error = "Email and name are required!";
            include(TEMPLATE_DIR . '/Home/admin.php');
        } else {
            $username = strtolower($first_name . '.' . $last_name);
            $password = "changeme" . rand(0,999);

            try {
                $user = Models\User::create($username, $first_name, $last_name, $email, $password, TRUE, $studentrnd_email_enabled, $is_admin);
                $user->groupIDs = $groups;
                $user->update();

                global $config;

                $sitename = $config['app']['name'];
                $domain = $config['app']['domain'];
                $url = \CuteControllers\Router::link('/login', TRUE);

                $srnd_email_email = <<<EMAIL
Additionally, you're all set to go with your apps account. You can use this to collaborate with other members. This also
comes with an email:

$username@$domain

!!! Be warned !!! Your email won't work until you sign in, click on the Email button, and accept the terms! So get to that!
EMAIL;

                if (!$studentrnd_email_enabled) $srnd_email_email = "";

                $email_body = <<<EMAIL
Welcome to $sitename!

You're all set up with a $sitename account! You can log in at $url. Here are your login details:

Username: $username
Password: $password

$srnd_email_email

Cheers!
EMAIL;

                mail($email, "Your My.StudentRND account has been provisioned!", $email_body, "From: $sitename <" . $config['app']['email'] . ">\r\n");
                $this->redirect('/admin');
            } catch (\Exception $ex) {
                $error = $ex->getMessage();
                include(TEMPLATE_DIR . '/Home/admin.php');
            }
        }
    }

    public function __post_create_plan()
    {
        $name = $this->request->post('name');
        $description = $this->request->post('description');
        $stripe_id = $this->request->post('stripe_id');
        $amount = $this->request->post('amount');
        $period = $this->request->post('period');

        if (!$name || !$description || !$stripe_id || !$amount || !$period) {
            $error = 'All fields are required!';
            include(TEMPLATE_DIR . '/Home/admin.php');
        } else {
            try {
                \StudentRND\My\Models\Plan::create($name, $description, $stripe_id, $amount, $period);
                $this->redirect('/admin');
            } catch (\Exception $ex) {
                $error = $ex->getMessage();
                include(TEMPLATE_DIR . '/Home/admin.php');
            }
        }
    }

    public function __post_update_plan()
    {
        if ($this->request->post('delete')) {
            return $this->post_delete_plan();
        }

        $planID = $this->request->post('planID');
        $description = $this->request->post('description');
        $stripe_id = $this->request->post('stripe_id');
        $name = $this->request->post('name');
        $amount = $this->request->post('amount');
        $period = strtolower($this->request->post('period'));

        if (!$name || !$description || !$stripe_id || !$amount || !$period) {
            $error = 'All fields are required!';
            include(TEMPLATE_DIR . '/Home/admin.php');
        } else {
            try {
                $plan = new \StudentRND\My\Models\Plan($planID);
                $plan->name = $name;
                $plan->description = $description;
                $plan->stripe_id = $stripe_id;
                $plan->amount = $amount;
                $plan->period = $period;
                $plan->update();
                $this->redirect('/admin');
            } catch (\Exception $ex) {
                $error = $ex->getMessage();
                include(TEMPLATE_DIR . '/Home/admin.php');
            }
        }
    }

    public function __post_delete_plan()
    {
        $planID = $this->request->post('planID');
        try {
            $plan = new \StudentRND\My\Models\Plan($planID);
            $plan->delete();
            $this->redirect('/admin');
        } catch (\Exception $ex) {
            $error = $ex->getMessage();
            include(TEMPLATE_DIR . '/Home/admin.php');
        }
    }

    public function __post_update_group()
    {
        if ($this->request->post('delete')) {
            return $this->post_delete_group();
        }

        $groupID = $this->request->post('groupID');
        $name = $this->request->post('name');
        $description = $this->request->post('description');
        $type = $this->request->post('type');
        $has_profile_badge = $this->request->post('has_profile_badge') ? TRUE : FALSE;

        if (!$name || !$description || !$type) {
            $error = "Name and description are required";
            include(TEMPLATE_DIR . '/Home/admin.php');
        } else {
            try {
                $group = new \StudentRND\My\Models\Group($groupID);
                $group->name = $name;
                $group->description = $description;
                $group->type = $type;
                $group->has_profile_badge = $has_profile_badge;
                $group->update();
                $this->redirect('/admin');
            } catch (\Exception $ex) {
                $error = $ex->getMessage();
                include(TEMPLATE_DIR . '/Home/admin.php');
            }
        }
    }

    public function __post_create_group()
    {
        $name = $this->request->post('name');
        $description = $this->request->post('description');
        $type = $this->request->post('type');
        $has_profile_badge = $this->request->post('has_profile_badge') ? TRUE : FALSE;

        if (!$name || !$description) {
            $error = "Name and description are required";
            include(TEMPLATE_DIR . '/Home/admin.php');
        } else {
            try {
                \StudentRND\My\Models\Group::create($name, $description, $type, $has_group_page);
                $this->redirect('/admin');
            } catch (\Exception $ex) {
                $error = $ex->getMessage();
                include(TEMPLATE_DIR . '/Home/admin.php');
            }
        }
    }

    public function __post_delete_group()
    {
        $groupID = $this->request->post('groupID');
        try {
            $group = new \StudentRND\My\Models\Group($groupID);
            $group->delete();
            $this->redirect('/admin');
        } catch (\Exception $ex) {
            $error = $ex->getMessage();
            include(TEMPLATE_DIR . '/Home/admin.php');
        }
    }
}
