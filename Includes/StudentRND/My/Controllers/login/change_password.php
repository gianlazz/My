<?php

namespace StudentRND\My\Controllers\login;

class change_password
{
    public function __get_index()
    {
        $error = "You are required to change your password before you can log in.";
        require_once(TEMPLATE_DIR . '/Login/change_password.php');
    }

    public function __post_index()
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
}
