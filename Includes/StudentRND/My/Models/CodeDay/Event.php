<?php

namespace StudentRND\My\Models\CodeDay;
use \StudentRND\My\Models\CodeDay;

class Event extends \TinyDb\Orm
{
    public static $table_name = "codeday_event";
    public static $primary_key = "eventID";

    protected $eventID;
    protected $name;
    protected $tagline;

    protected $start_time;
    protected $end_time;
    protected $location_name;
    protected $location_address;

    protected $eventbrite_id;

    public static function create($name, $tagline, $start_time, $end_time, $location_name, $location_address, $eventbrite_id)
    {
        return parent::create(array(
                              'name' => $name,
                              'tagline' => $tagline,
                              'start_time' => $start_time,
                              'end_time' => $end_time,
                              'location_name' => $location_name,
                              'location_address' => $location_address,
                              'eventbrite_id' => $eventbrite_id));
    }

    public function __get_blocks()
    {
        return new \TinyDb\Collection('\StudentRND\My\Models\CodeDay\Block', \TinyDb\Sql::create()
                                      ->select('*')
                                      ->from(CodeDay\Block::$table_name)
                                      ->where('eventID = ?', $this->eventID)
                                      ->order_by('sort ASC'));
    }

    public function __get_sponsors()
    {
        return new \TinyDb\Collection('\StudentRND\My\Models\CodeDay\Sponsor', \TinyDb\Sql::create()
                                      ->select('*')
                                      ->from(CodeDay\Sponsor::$table_name)
                                      ->where('eventID = ?', $this->eventID)
                                      ->order_by('sort ASC'));
    }

    /**
     * Gets a schedule
     * @param  string $type 'schedule', 'workshops', or 'pre-workshops'
     */
    public function get_schedule($type)
    {
        //
    }

}
