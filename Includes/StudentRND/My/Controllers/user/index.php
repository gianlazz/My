<?php

namespace StudentRND\My\Controllers;

use \StudentRND\My\Models;

class index extends \CuteControllers\Base\Rest
{
    public function before()
    {
        $this->user = \StudentRND\My\Models\User::current();
        if ($this->request->get('username') !== NULL) {
            $this->user = new \StudentRND\My\Models\User(array('username' => $this->request->get('username')));
        }
    }

    public function __get_index()
    {
        if (Models\User::current()->is_admin || Models\User::current()->userID == $this->user->userID) {
            require_once(TEMPLATE_DIR . '/Home/user/edit.php');
        } else {
            require_once(TEMPLATE_DIR . '/Home/user/show.php');
        }
    }

    public function __post_index()
    {
        if ($this->user->userID !== Models\User::current()->userID && !Models\User::current()->is_admin) {
            throw new \CuteControllers\HttpError(403);
        }

        $first_name = $this->request->post('first_name');
        $last_name = $this->request->post('last_name');
        $email = $this->request->post('email');
        $password_reset_required = $this->request->post('password_reset_required') ? TRUE : FALSE;
        $studentrnd_email_enabled = $this->request->post('studentrnd_email_enabled') ? TRUE : FALSE;
        $is_admin = $this->request->post('is_admin') ? TRUE : FALSE;
        $is_disabled = $this->request->post('is_disabled') ? TRUE : FALSE;
        $avatar_url = $this->request->post('avatar_url');
        $groups = $this->request->post('groups');
        $phone = $this->request->post('phone');
        $address1 = $this->request->post('address1');
        $address2 = $this->request->post('address2');
        $city = $this->request->post('city');
        $state = $this->request->post('state');
        $zip = $this->request->post('zip');
        $twitter_id = $this->request->post('twitter_id');
        $linkedin_id = $this->request->post('linkedin_id');

        if (!$first_name || !$last_name || !$email) {
            $error = "First name, last name, and email are required.";
            require_once(TEMPLATE_DIR . '/Home/user/edit.php');
        } else {
            if ($avatar_url !== $this->user->avatar_url) {
                $filename = '/' . md5($avatar_url . time()) . '.jpg';
                file_put_contents(UPLOADS_DIR . $filename, file_get_contents($avatar_url));
                $avatar_url = UPLOADS_URI . $filename;
            }
            try {
                $this->user->first_name = $first_name;
                $this->user->last_name = $last_name;
                $this->user->email = $email;

                $this->user->phone = $phone;
                $this->user->address1 = $address1;
                $this->user->address2 = $address2;
                $this->user->city = $city;
                $this->user->state = $state;
                $this->user->zip = $zip;

                $this->user->twitter_id = $twitter_id;
                $this->user->linkedin_id = $linkedin_id;

                // Only admins can edit some fields
                if (Models\User::current()->is_admin) {
                    $this->user->password_reset_required = $password_reset_required;
                    $this->user->studentrnd_email_enabled = $studentrnd_email_enabled;
                    $this->user->is_disabled = $is_disabled;

                    // Don't add/remove admin status for the current user.
                    if ($this->user->userID !== Models\User::current()->userID) {
                        $this->user->is_admin = $is_admin;
                    }


                    $this->user->groupIDs = $groups;
                }
                $this->user->avatar_url = $avatar_url;
                $this->user->update();
                $this->redirect('?username=' . $this->user->username);
            } catch (\CuteControllers\HttpError $ex) {
                $error = $ex->getMessage();
                require_once(TEMPLATE_DIR . '/Home/user/edit.php');
            }
        }
    }
}
