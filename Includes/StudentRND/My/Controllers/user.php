<?php

namespace StudentRND\My\Controllers;

use \StudentRND\My\Models;

class user extends \CuteControllers\Base\Rest
{
    public function before()
    {
        $this->user = \StudentRND\My\Models\User::current();
        if ($this->request->get('username') !== NULL) {
            $this->user = new \StudentRND\My\Models\User(array('username' => $this->request->get('username')));
        }
    }

    public function get_password()
    {
        include(TEMPLATE_DIR . '/Home/change_password.php');
    }

    public function post_password()
    {
        $current = $this->request->post('current');
        $password = $this->request->post('password');
        $password_confirm = $this->request->post('password2');

        if (!Models\User::current()->is_admin && $this->user->userID !== Models\User::current()->userID) {
            $error = "You can't do that!";
            require_once(TEMPLATE_DIR . '/Home/change_password.php');
        } if (!isset($password) || !isset($password_confirm)) {
            $error = "Please enter a password.";
            require_once(TEMPLATE_DIR . '/Home/change_password.php');
        } else if($password !== $password_confirm) {
            $error = "Passwords did not match.";
            require_once(TEMPLATE_DIR . '/Home/change_password.php');
        } else if(!\StudentRND\My\Models\User::current()->validate_password($current)) {
            $error = "Current password was incorrect.";
            require_once(TEMPLATE_DIR . '/Home/change_password.php');
        } else {
            try {
                $this->user->password = $password;
                $this->user->update();
                \CuteControllers\Router::redirect('/home');
            } catch (\Exception $ex){
                $error = $ex->getMessage();
                require_once(TEMPLATE_DIR . '/Home/change_password.php');
            }
        }
    }

    public function get_rfids()
    {
        if (!Models\User::current()->is_admin) {
            throw new \CuteControllers\HttpError(401);
        } else if (!isset($this->user)) {
            throw new \CuteControllers\HttpError(404);
        } else {
            include(TEMPLATE_DIR . '/Home/manage_rfids.php');
        }
    }

    public function post_rfids()
    {
        if (!Models\User::current()->is_admin) {
            throw new \CuteControllers\HttpError(401);
        } else if (!isset($this->user)) {
            throw new \CuteControllers\HttpError(404);
        } else {
            $rfid_hex = $this->request->post('rfID');
            $rfid_plaintext = $this->request->post('rfID_plaintext');

            if (isset($rfid_plaintext)) {
                if (preg_match('/.*[A-Fa-f]+.*/', $rfid_plaintext)) {
                    $rfid_hex = $rfid_plaintext;
                } else {
                    $rfid_hex = "4F00" . dechex(intval($rfid_plaintext));
                }
            }

            try {
                $rfid = Models\Rfid::create($rfid_hex, $this->user);
                $this->redirect('');
            } catch (\Exception $ex) {
                $error = "Could not add token -- already associated with another user.";
                include(TEMPLATE_DIR . '/Home/manage_rfids.php');
            }
        }
    }

    public function post_rfid_delete()
    {
        if (!Models\User::current()->is_admin) {
            throw new \CuteControllers\HttpError(401);
        } else if (!isset($this->user)) {
            throw new \CuteControllers\HttpError(404);
        } else {
            $rfid = new Models\Rfid($this->request->post('rfID'));
            $rfid->delete();
            $this->redirect('/user/rfids?username=' . $this->user->username);
        }
    }

    public function get_applications()
    {
        include(TEMPLATE_DIR . '/Home/manage_applications.php');
    }

    public function post_create_application()
    {
        if (!Models\User::current()->is_admin) {
            throw new \CuteControllers\HttpError(404);
        } else {
            if ($this->request->post('name')) {
                Models\Application::create($this->request->post('name'), $this->user);
                $this->redirect('/user/applications?username=' . $this->user->username);
            } else {
                $error = "You must specify an application name";
                include(TEMPLATE_DIR . '/Home/manage_applications.php');
            }
        }
    }

    public function post_send_password_reset_email()
    {
        if (!Models\User::current()->is_admin) {
            throw new \CuteControllers\HttpError(401);
        } else {
            $new_password = static::_get_random_password();
            $this->user->password = $new_password;
            $this->user->password_reset_required = TRUE;
            $this->user->update();
            mail($this->user->email, "Your My.StudentRND password has been reset!",
                 "Your password has been reset for My.StudentRND. It is now: $new_password\n\n" .
                    "You can log in at http://my.studentrnd.org/ with the username {$this->user->username} and your new password.",
                 "From: my@studentrnd.org");
            $this->redirect('/user?username=' . $this->user->username);
        }
    }

    private static function _get_random_password($len = 10)
    {
        $dict = file("/usr/share/dict/words");
        $pass = rand(0,9);
        while (strlen($pass) < $len) {
            $pass .= ucfirst(preg_replace("/[^A-Za-z0-9 ]/", '', $dict[rand(0, count($dict) - 1)]));
        }
        return $pass;
    }

    public function get_impersonate()
    {
        $username = $this->request->get('username');
        $user = new Models\User(array('username' => $username));

        if (Models\User::current()->is_admin && !$user->is_admin) {
            $user->impersonate();
            $this->redirect('/');
        } else {
            throw new \CuteControllers\HttpError(401);
        }
    }

    public function get_deimpersonate()
    {
        if (Models\User::is_impersonating()) {
            Models\User::deimpersonate();
            $this->redirect('/');
        } else {
            throw new \CuteControllers\HttpError(404);
        }
    }

    public function get_index()
    {
        if (Models\User::current()->is_admin || Models\User::current()->userID == $this->user->userID) {
            require_once(TEMPLATE_DIR . '/Home/edit_user.php');
        } else {
            require_once(TEMPLATE_DIR . '/Home/show_user.php');
        }
    }

    public function get_oauth()
    {
        global $config;
        $service = $this->request->get('service');

        $redirect_uri = $config['app']['url'] . substr(\CuteControllers\Router::get_link('/user/oauth_callback?service=' . $service), 1);


        switch($service) {
            case "fb":
                $client_id = $config['fb']['client_id'];
                $url = "https://www.facebook.com/dialog/oauth/?client_id=$client_id&redirect_uri=$redirect_uri&scope=read_friendlists";
                $this->redirect($url);
                exit;
                break;
            default:
                throw new \CuteControllers\HttpError(404);
        }
    }

    public function get_deoauth()
    {
        if ($this->user->userID !== Models\User::current()->userID) {
            throw new \CuteControllers\HttpError(401);
        }

        $service = $this->request->get('service');

        switch($service) {
            case "fb":
                $this->user->fb_id = NULL;
                $this->user->fb_access_token = NULL;
                break;
            default:
                throw new \CuteControllers\HttpError(404);
        }

        $this->user->update();

        $this->redirect('/user');
    }

    public function get_oauth_callback()
    {
        global $config;

        if ($this->user->userID !== Models\User::current()->userID) {
            throw new \CuteControllers\HttpError(401);
        }

        $service = $this->request->get('service');
        $redirect_uri = $config['app']['url'] . substr(\CuteControllers\Router::get_link('/user/oauth_callback?service=' . $service), 1);

        switch($service) {
            case "fb":
                $code = $this->request->get('code');
                $client_id = $config['fb']['client_id'];
                $client_secret = $config['fb']['client_secret'];

                $url = "https://graph.facebook.com/oauth/access_token?client_id=$client_id&client_secret=$client_secret&code=$code&redirect_uri=$redirect_uri";
                $response = file_get_contents($url);
                $params = null;
                parse_str($response, $params);

                $this->user->fb_access_token = $params['access_token'];

                $graph_url = "https://graph.facebook.com/me?access_token=" . $params['access_token'];
                $fb_user = json_decode(file_get_contents($graph_url));
                $this->user->fb_id = $fb_user->id;
                break;
            default:
                throw new \CuteControllers\HttpError(404);
        }


        $this->user->update();
        $this->redirect('/user');
    }

    public function get_access_grants()
    {
        if (!Models\User::current()->is_admin) {
            throw new \CuteControllers\WebError(404);
        }

        $form = new \FastForms\Form('\StudentRND\My\Models\AccessGrant');
        $form->static_values = array('userID' => $this->user->userID);

        include(TEMPLATE_DIR . '/Home/manage_access_grants.php');
    }

    public function post_delete_access_grant()
    {
        if (!Models\User::current()->is_admin) {
            throw new \CuteControllers\WebError(404);
        }

        $grant = new Models\AccessGrant($this->request->post('grantID'));
        $grant->delete();

        $this->redirect('/user/access_grants?username=' . $this->user->username);
    }

    public function post_add_access_grant()
    {
        if (!Models\User::current()->is_admin) {
            throw new \CuteControllers\WebError(404);
        }

        $form = new \FastForms\Form('\StudentRND\My\Models\AccessGrant');
        $form->static_values = array('userID' => $this->user->userID);
        try {
            $new_obj = $form->create();
            $this->redirect('/user/access_grants?username=' . $this->user->username);
        } catch (\Exception $ex) {
            $error = $ex->getMessage();
            include(TEMPLATE_DIR . '/Home/manage_access_grants.php');
            return;
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
                require_once(TEMPLATE_DIR . '/Home/edit_user.php');
            }
        }
    }
}
