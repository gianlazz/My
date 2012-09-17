<?php

namespace StudentRND\My\Models\CodeDay;

use \StudentRND\My\Models\CodeDay;

class Sponsor extends \TinyDb\Orm
{
    public static $table_name = "codeday_sponsors";
    public static $primary_key = "sponsorID";

    protected $sponsorID;
    protected $name;
    protected $logo;
    protected $link;
    protected $sort;

    protected $eventID;

    public static function create($name, $logo, $link, CodeDay\Event $event)
    {
        return parent::create(array(
                              'name' => $name,
                              'logo' => $logo,
                              'link' => $link,
                              'sort' => 1000,
                              'eventID' => $event->eventID));
    }

    public function __get_event()
    {
        return new CodeDay\Event($this->eventID);
    }
}
