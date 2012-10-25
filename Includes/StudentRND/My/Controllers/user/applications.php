<?php

namespace StudentRND\My\Controllers\user;

use \StudentRND\My\Models;

class applications extends \CuteControllers\Base\Rest
{
    public function before()
    {
        $this->user = new Models\User(array('username' => $this->request->request('username')));
    }

    public function __get_index()
    {
        include(TEMPLATE_DIR . '/Home/user/applications.php');
    }

    public function __post_create()
    {
        if (!Models\User::current()->is_admin) {
            throw new \CuteControllers\HttpError(403);
        } else {
            if ($this->request->post('name')) {
                Models\Application::create($this->request->post('name'), $this->user);
                $this->redirect('/user/applications?username=' . $this->user->username);
            } else {
                $error = "You must specify an application name";
                include(TEMPLATE_DIR . '/Home/user/applications.php');
            }
        }
    }
}
