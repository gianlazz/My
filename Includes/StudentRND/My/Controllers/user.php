<?php

namespace StudentRND\My\Controllers;

use \StudentRND\My\Models;

class user extends \CuteControllers\Base\Rest
{
    public function before()
    {
        $this->user = new Models\User(array('username' => $this->request->get('username')));
    }
    public function get_index()
    {
        if (Models\User::current()->is_admin || Models\User::current()->userID == $this->user->userID) {
            require_once(TEMPLATE_DIR . '/Home/edit_user.php');
        } else {
            require_once(TEMPLATE_DIR . '/Home/show_user.php');
        }
    }

    public function post_index()
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
        $avatar_url = $this->request->post('avatar_url');

        if (!$first_name || !$last_name || !$email) {
            $error = "First name, last name, and email are required.";
            require_once(TEMPLATE_DIR . '/Home/edit_user.php');
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

                // Only admins can edit some fields
                if (Models\User::current()->is_admin) {
                    $this->user->password_reset_required = $password_reset_required;
                    $this->user->studentrnd_email_enabled = $studentrnd_email_enabled;

                    // Don't add/remove admin status for the current user.
                    if ($this->user->userID !== Models\User::current()->userID) {
                        $this->user->is_admin = $is_admin;
                    }
                }
                $this->user->avatar_url = $avatar_url;
                $this->user->update();
                $this->redirect('?username=' . $this->user->username);
            } catch (\Exception $ex) {
                $error = $ex->getMessage();
                require_once(TEMPLATE_DIR . '/Home/edit_user.php');
            }
        }
    }
}
