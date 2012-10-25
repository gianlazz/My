<?php

namespace StudentRND\My\Controllers\user;

use \StudentRND\My\Models;

class index extends \CuteControllers\Base\Rest
{
    public function before()
    {
        $this->user = new Models\User(array('username' => $this->request->request('username')));

        if (!Models\User::current()->is_admin && $this->user->userID !== Models\User::current()->userID) {
            throw new \CuteControllers\HttpError(403);
        }
    }

    public function __get_index()
    {
        include(TEMPLATE_DIR . '/Home/user/rfids.php');
    }

    public function __post_index()
    {
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
            include(TEMPLATE_DIR . '/Home/user/rfids.php');
        }
    }

    public function __post_delete()
    {
        $rfid = new Models\Rfid($this->request->post('rfID'));
        $rfid->delete();
        $this->redirect('/user/rfids?username=' . $this->user->username);
    }
}
