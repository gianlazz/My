<?php

namespace StudentRND\My\Controllers;

class index extends \CuteControllers\Base\Web
{
    public function __index()
    {
        if (\StudentRND\My\Models\User::is_logged_in()) {
            $this->redirect('/home');
        } else {
            $this->redirect('/login');
        }
    }
}
