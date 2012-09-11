<?php

namespace StudentRND\My\Controllers;

class index extends \CuteControllers\Base\Rest
{
    public function get_index()
    {
        if (\StudentRND\My\Models\User::is_logged_in()) {
            $this->redirect('/home');
        }

        require_once(TEMPLATE_DIR . '/Login/index.php');
    }

    public function get_bye()
    {
        if (!\StudentRND\My\Models\User::is_logged_in()) {
            $this->redirect('/login');
        }

        $_SESSION['userID'] = null;
        $error = "You have been logged out.";
        $logout = true;
        require_once(TEMPLATE_DIR . '/Login/index.php');
    }

    public function get_change_password()
    {
        $error = "You are required to change your password before you can log in.";
        require_once(TEMPLATE_DIR . '/Login/change_password.php');
    }

    public function post_change_password()
    {
        $password = $this->request->post('password');
        $password_confirm = $this->request->post('password2');

        if (!isset($password) || !isset($password_confirm)) {
            $error = "Please enter a password.";
            require_once(TEMPLATE_DIR . '/Login/change_password.php');
        } else if($password !== $password_confirm) {
            $error = "Passwords did not match.";
            require_once(TEMPLATE_DIR . '/Login/change_password.php');
        } else {
            try {
                $user = \StudentRND\My\Models\User::current();
                $user->password = $password;
                $user->update();
                \CuteControllers\Router::redirect('/home');
            } catch (\Exception $ex){
                $error = $ex->getMessage();
                require_once(TEMPLATE_DIR . '/Login/change_password.php');
            }
        }
    }

    public function redirect($to)
    {
        if ($to === "/home")
        {
            if ($this->request->request('return')) {
                $to = $this->request->request('return');
            }
        } else if($this->request->request('return') && (strpos($to, '?') === FALSE || strpos(substr($to, strrpos($to, '?')), 'return=') === FALSE)) {
            if (strpos($to, '?') === FALSE) {
                $to .= '?';
            } else {
                $to .= '&';
            }
            $to .= 'return=' . urlencode($this->request->request('return'));
        }

        parent::redirect($to);
    }

    public function post_index()
    {
        try {
            $user = new \StudentRND\My\Models\User(array('username' => $this->request->post('username')));
        } catch (\TinyDb\NoRecordException $ex) { }

        if (isset($user) && $user->validate_password($this->request->post('password'))) {
            $user->login();
            if ($user->password_reset_required) {
                $this->redirect('/login/change_password');
            } else {
                $this->redirect('/home');
            }
        } else {
            $error = "Login not found. Check your username and password and try again.";
            require_once(TEMPLATE_DIR . '/Login/index.php');
        }
    }
}
