<?php

namespace StudentRND\My\Controllers;

use \StudentRND\My\Models;

class index extends \CuteControllers\Base\Rest
{
    public function get_index()
    {
        if (Models\User::is_logged_in()) {
            $this->redirect('/home');
        }

        require_once(TEMPLATE_DIR . '/Login/index.php');
    }

    public function get_bye()
    {
        if (!Models\User::is_logged_in()) {
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
                $user = Models\User::current();
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

    private static function parse_signed_request($signed_request) {
        global $config;
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        // decode the data
        $sig = self::base64_url_decode($encoded_sig);
        $data = json_decode(self::base64_url_decode($payload), true);

        if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
            echo "Invalid algo";
            exit;
        }

        $expected_sig = hash_hmac('sha256', $payload, $config['fb']['client_secret'], $raw = true);
        if ($sig !== $expected_sig) {
            echo "Invalid sig";
            exit;
        }

        return $data;
    }

    private static function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public function post_canvas()
    {
        $data = self::parse_signed_request($this->request->post('signed_request'));
        if (!$data) {
            $this->redirect('/');
        } else {
            try {
                $user = new Models\User(array('fb_id' => $data['user_id']));
                $this->_login($user);
            } catch (\TinyDb\NoRecordException $ex) {
                $error = "You have not yet associated your account with Facebook.";
                require_once(TEMPLATE_DIR . '/Login/index.php');
            }
        }
    }

    private function _login($user)
    {
        $user->login();
        if ($user->password_reset_required) {
            $this->redirect('/login/change_password');
        } else {
            $this->redirect('/home');
        }
    }

    public function post_index()
    {
        if ($this->request->post('username')) {
            try {
                $user = new Models\User(array('username' => $this->request->post('username')));
                if ($user->validate_password($this->request->post('password'))) {
                    $this->_login($user);
                }
            } catch (\TinyDb\NoRecordException $ex) { }

        } else if ($this->request->post('rfid-token')) {
            try {
                $rfid = new \StudentRND\My\Models\Rfid($this->request->post('rfid-token'));
                $this->_login($rfid->user);
            } catch (\TinyDb\NoRecordException $ex) { }

        }

        $error = "Login not found. Check your username and password and try again.";
        require_once(TEMPLATE_DIR . '/Login/index.php');
    }
}
