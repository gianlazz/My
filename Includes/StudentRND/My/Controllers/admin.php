<?php

namespace StudentRND\My\Controllers;

class admin extends \CuteControllers\Base\Rest
{
    public function before()
    {
        $this->user = \StudentRND\My\Models\User::current();
        if (!$this->user->is_admin) {
            throw new \CuteControllers\HttpError(403);
        }
    }

    public function get_index()
    {
        require_once(TEMPLATE_DIR . '/Home/admin.php');
    }

    public function post_create_plan()
    {
        $name = $this->request->post('name');
        $amount = $this->request->post('amount');
        $period = $this->request->post('period');
        $groupID = $this->request->post('groupID');

        if (!$name || !$amount || !$period || !$groupID) {
            $error = 'All fields are required!';
            require_once(TEMPLATE_DIR . '/Home/admin.php');
        } else {
            try {
                \StudentRND\My\Models\Plan::create($name, $amount, $period, $groupID);
                $this->redirect('/admin');
            } catch (\Exception $ex) {
                $error = $ex->getMessage();
                require_once(TEMPLATE_DIR . '/Home/admin.php');
            }
        }
    }

    public function post_update_plan()
    {
        $planID = $this->request->post('planID');
        $name = $this->request->post('name');
        $amount = $this->request->post('amount');
        $period = strtolower($this->request->post('period'));
        $groupID = intval($this->request->post('groupID'));

        if (!$name || !$amount || !$period || !$groupID) {
            $error = 'All fields are required!';
            require_once(TEMPLATE_DIR . '/Home/admin.php');
        } else {
            try {
                $plan = new \StudentRND\My\Models\Plan($planID);
                $plan->name = $name;
                $plan->amount = $amount;
                $plan->period = $period;
                $plan->groupID = $groupID;
                $plan->update();
                $this->redirect('/admin');
            } catch (\Exception $ex) {
                $error = $ex->getMessage();
                require_once(TEMPLATE_DIR . '/Home/admin.php');
            }
        }
    }

    public function post_update_group()
    {
        $groupID = $this->request->post('groupID');
        $name = $this->request->post('name');
        $description = $this->request->post('description');
        $has_group_page = $this->request->post('has_group_page') ? TRUE : FALSE;
        $has_profile_badge = $this->request->post('has_profile_badge') ? TRUE : FALSE;

        if (!$name || !$description) {
            $error = "Name and description are required";
            require_once(TEMPLATE_DIR . '/Home/admin.php');
        } else {
            try {
                $group = new \StudentRND\My\Models\Group($groupID);
                $group->name = $name;
                $group->description = $description;
                $group->has_group_page = $has_group_page;
                $group->has_profile_badge = $has_profile_badge;
                $group->update();
                $this->redirect('/admin');
            } catch (\Exception $ex) {
                $error = $ex->getMessage();
                require_once(TEMPLATE_DIR . '/Home/admin.php');
            }
        }
    }

    public function post_create_group()
    {
        $name = $this->request->post('name');
        $description = $this->request->post('description');
        $has_group_page = $this->request->post('has_group_page') ? TRUE : FALSE;
        $has_profile_badge = $this->request->post('has_profile_badge') ? TRUE : FALSE;

        if (!$name || !$description) {
            $error = "Name and description are required";
            require_once(TEMPLATE_DIR . '/Home/admin.php');
        } else {
            try {
                \StudentRND\My\Models\Group::create($name, $description, $has_group_page, $has_profile_badge);
                $this->redirect('/admin');
            } catch (\Exception $ex) {
                $error = $ex->getMessage();
                require_once(TEMPLATE_DIR . '/Home/admin.php');
            }
        }
    }
}
