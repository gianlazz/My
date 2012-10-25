<?php

namespace StudentRND\My\Controllers\user;

use \StudentRND\My\Models;

class access_grants extends \CuteControllers\Base\Rest
{
    public function __get_index()
    {
        if (!Models\User::current()->is_admin) {
            throw new \CuteControllers\HttpError(403);
        }

        $username = $this->request->get('username');
        $user = new Models\User(array('username' => $username));

        if (Models\User::current()->is_admin) {
            $user->impersonate();
            $this->redirect('/');
        } else {
            throw new \CuteControllers\HttpError(401);
        }
    }

    public function __get_bye()
    {
        if (Models\User::is_impersonating()) {
            Models\User::deimpersonate();
            $this->redirect('/');
        } else {
            throw new \CuteControllers\HttpError(404);
        }
    }
}
