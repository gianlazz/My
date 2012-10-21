<?php

namespace StudentRND\My\Models;

use \StudentRND\My\Models;

class ApplicationNonce extends \TinyDb\Orm
{
    public static $table_name = 'application_nonces';
    public static $primary_key = array('applicationID', 'nonce');

    public $applicationID;
    public $nonce;

    public static function create(Models\Application $app, $nonce)
    {
        return parent::create(array(
            'applicationID' => $app->applicationID,
            'nonce' => $nonce,
        ));
    }
}
