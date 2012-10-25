<?php

namespace StudentRND\My\Controllers\api;

use \StudentRND\My\Models;

class access_control extends \CuteControllers\Base\Rest
{
    public function before()
    {
        header("Content-type: text/plain");
        Models\Application::validate_request();
    }

    public function get_approved_list()
    {
        $users = new \TinyDb\Collection('\StudentRND\My\Models\User', \TinyDb\Sql::create()
                                             ->select('*')
                                             ->from(Models\User::$table_name));

        $acl = array();

        foreach ($users as $user) {
            $acl[] = $this->_get_user_data_contract($user);
        }

        $contract_acl = array(
            "acl" => $acl
        );

        echo json_encode($contract_acl);
    }

    private function _get_user_data_contract(Models\User $user) {
        $contract_user = array(
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
            "username" => $user->username,
            "twitter_id" => $user->twitter_id,
            "rfids" => array()
        );

        if ($user->has_group(new Models\Group(10))) {
            $contract_user["unlimited_access"] = true;
        } else {
            $contract_user["unlimited_access"] = false;
            foreach ($user->access_grants as $grant) {
                $contract_user["access_grants"][] = array(
                        "start" => $grant->start,
                        "end" => $grant->end
                    );
            }
        }

        foreach ($user->rfids as $rfid) {
            $contract_user["rfids"][] = $rfid->rfID;
        }

        return $contract_user;
    }
}
