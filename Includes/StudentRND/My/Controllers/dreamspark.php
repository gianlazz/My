<?php

namespace StudentRND\My\Controllers;

class dreamspark extends \CuteControllers\Base\Rest
{
    public function before()
    {
        $this->user = \StudentRND\My\Models\User::current();
    }

    public function get_login()
    {
        if (count($this->user->groups) == 0) {
            throw new \CuteControllers\HttpError(403);
        }

        $this->redirect($this->get_login_url());
    }

    private function get_login_url(){
        global $config;

        $url = "https://e5.onthehub.com/WebStore/Security/AuthenticateUser.aspx?";
        $url .= "account=" . $config['elms']['account'];
        $url .= "&username=" . $this->user->username . '@' . $config['app']['domain'];
        $url .= "&key=" . $config['elms']['key'];
        $url .= "&academic_statuses=students";
        $url .= "&email=" . $this->user->email;
        $url .= "&last_name=" . $this->user->first_name;
        $url .= "&first_name=" . $this->user->last_name;

        $result = file_get_contents($url);

        if(substr($result, 0, 4) != "http"){
            throw new \Exception($url . ": " . $result);
        }

        return $result;
    }
}
