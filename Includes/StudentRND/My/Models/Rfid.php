<?php

namespace StudentRND\My\Models;

class Rfid extends \TinyDb\Orm
{
    public static $table_name = 'rfids';
    public static $primary_key = 'rfID';

    public $rfID;
    public $userID;
}
