<?php

namespace StudentRND\My\Models;

use \StudentRND\My\Models;

class Application extends \TinyDb\Orm
{
    public static $table_name = 'applications';
    public static $primary_key = 'applicationID';

    public $applicationID;
    public $key;
    public $name;
    public $userID;

    public static function validate_request()
    {
        $applicationID = $_GET['application_id'];
        $application = new self($applicationID);

        $nonce = $_GET['time'] . $_GET['nonce'];
        $signature = $_GET['signature'];

        try {
            Models\ApplicationNonce::create($application, $nonce);
            if (hash('sha256', $nonce . $application->key) !== $signature) {
                header("Status: 400 Forbidden");
                echo "Invalid signature";
                exit;
            } else {
                return true;
            }
        } catch (\Exception $ex) {
            header("Status: 400 Forbidden");
            echo "Invalid signature";
            exit;
        }
    }

    public static function create($name, Models\User $user)
    {
        $public = hash('md5', time() . rand(0,100000));
        $private = hash('sha256', $public . rand(0,1000000) . time());
        return parent::create(array(
            'applicationID' => $public,
            'key' => $private,
            'name' => $name,
            'userID' => $user->userID
        ));
    }

    public function __get_user()
    {
        return new Models\User($this->userID);
    }
}
