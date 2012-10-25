<?php

namespace StudentRND\My\Controllers\user;

use \StudentRND\My\Models;

class password extends \CuteControllers\Base\Rest
{
    public function before()
    {
        if ($this->request->get('username') !== NULL) {
            $this->user = new \StudentRND\My\Models\User(array('username' => $this->request->get('username')));
        } else {
            $this->user = \StudentRND\My\Models\User::current();
        }

        if (!Models\User::current()->is_admin && $this->user->userID !== Models\User::current()->userID) {
            throw new \CuteControllers\HttpError(403);
        }
    }

    public function __post_send_reset_email()
    {
        if (!Models\User::current()->is_admin) {
            throw new \CuteControllers\HttpError(403);
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

    public function __get_index()
    {
        include(TEMPLATE_DIR . '/Home/user/change_password.php');
    }

    public function __post_index()
    {
        $current = $this->request->post('current');
        $password = $this->request->post('password');
        $password_confirm = $this->request->post('password2');

        if (!isset($password) || !isset($password_confirm)) {
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
                require_once(TEMPLATE_DIR . '/Home/user/change_password.php');
            }
        }
    }
}
