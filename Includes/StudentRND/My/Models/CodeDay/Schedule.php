<?php

namespace StudentRND\My\Models\CodeDay;

use \StudentRND\My\Models\CodeDay;

class Schedule extends \TinyDb\Orm
{
    public static $table_name = "codeday_schedules";
    public static $primary_key = "scheduleID";

    protected $scheduleID;
    protected $name;
    protected $date;
    protected $type;
    protected $activity_lead;

    protected $eventID;

    public static function create($name, $date, $type, $activity_lead, CodeDay\Event $event)
    {
        return parent::create(array(
                              'name' => $name,
                              'date' => $date,
                              'type' => $type,
                              'activity_lead' => $activity_lead,
                              'eventID' => $event->eventID));
    }

    public function __get_event()
    {
        return new CodeDay\Event($this->eventID);
    }
}
