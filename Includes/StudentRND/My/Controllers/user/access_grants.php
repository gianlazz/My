<?php

namespace StudentRND\My\Controllers\user;

use \StudentRND\My\Models;

class access_grants extends \CuteControllers\Base\Rest
{
    public function before()
    {
        $this->user = new Models\User(array('username' => $this->request->request('username')));

        if (!Models\User::current()->is_admin) {
            throw new \CuteControllers\HttpError(403);
        }
    }

    public function __get_index()
    {
        $form = new \FastForms\Form('\StudentRND\My\Models\AccessGrant');
        $form->static_values = array('userID' => $this->user->userID);

        include(TEMPLATE_DIR . '/Home/user/access_grants.php');
    }

    public function __post_delete()
    {
        $grant = new Models\AccessGrant($this->request->post('grantID'));
        $grant->delete();

        $this->redirect('/user/access_grants?username=' . $this->user->username);
    }

    public function __post_add()
    {
        $form = new \FastForms\Form('\StudentRND\My\Models\AccessGrant');
        $form->static_values = array('userID' => $this->user->userID);
        try {
            $new_obj = $form->create();
            $this->redirect('/user/access_grants?username=' . $this->user->username);
        } catch (\Exception $ex) {
            $error = $ex->getMessage();
            include(TEMPLATE_DIR . '/Home/user/access_grants.php');
            return;
        }
    }
}
