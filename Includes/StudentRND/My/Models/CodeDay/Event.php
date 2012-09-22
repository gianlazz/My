<?php

namespace StudentRND\My\Models\CodeDay;
use \StudentRND\My\Models\CodeDay;

class Event extends \TinyDb\Orm
{
    public static $table_name = "codeday_events";
    public static $primary_key = "eventID";

    protected $eventID;

    public static $__name_name = 'CodeDay Name';
    public static $__place_name = 'Portland';
    protected $name;

    public static $__class_tagline = 'input-xxlarge';
    protected $tagline;

    protected $start_date;
    protected $end_date;
    protected $location_name;
    protected $location_address;

    protected $eventbrite_id;

    protected $hero_background_url;
    protected $hero_foreground_color;

    public static function create($name, $tagline, $start_date, $end_date, $location_name, $location_address, $eventbrite_id,
                                  $hero_background_url, $hero_foreground_color)
    {
        return parent::create(array(
                            'name' => $name,
                            'tagline' => $tagline,
                            'start_date' => $start_date,
                            'end_date' => $end_date,
                            'location_name' => $location_name,
                            'location_address' => $location_address,
                            'hero_background_url' => $hero_background_url,
                            'hero_foreground_color' => $hero_foreground_color,
                            'eventbrite_id' => $eventbrite_id));
    }

    private $eventbrite_json = NULL;
    public function __get_eventbrite_data()
    {
        if (!isset($this->eventbrite_json)) {
            global $config;
            $this->eventbrite_json = json_decode(file_get_contents('https://www.eventbrite.com/json/event_get?' .
                                                                        'app_key=' . $config['eventbrite']['app_key'] .
                                                                        '&user_key=' . $config['eventbrite']['user_key'] .
                                                                        '&id=' . $this->eventbrite_id))->event;
        }

        return $this->eventbrite_json;
    }

    public function __get_tickets_sold()
    {
        return $this->eventbrite_data->num_attendee_rows;
    }

    public function __get_tickets_available()
    {
        return $this->eventbrite_data->capacity;
    }

    public function __get_is_ended()
    {
        return $this->end_date < time();
    }

    public function __get_is_now()
    {
        return $this->start_date < time() && $this->end_date > time();
    }

    public function __get_is_sold_out()
    {
        return $this->tickets_sold >= $this->tickets_available;
    }

    public function __get_is_presale()
    {
        return $this->start_date > time();
    }

    public function __get_blocks()
    {
        return new \TinyDb\Collection('\StudentRND\My\Models\CodeDay\Block', \TinyDb\Sql::create()
                                    ->select('*')
                                    ->from(CodeDay\Block::$table_name)
                                    ->where('eventID = ?', $this->eventID)
                                    ->order_by('sort ASC'));
    }

    public function __get_faqs()
    {
        return new \TinyDb\Collection('\StudentRND\My\Models\CodeDay\Faq', \TinyDb\Sql::create()
                                    ->select('*')
                                    ->from(CodeDay\Faq::$table_name)
                                    ->where('eventID = ?', $this->eventID));
    }

    public function __get_sponsors()
    {
        return new \TinyDb\Collection('\StudentRND\My\Models\CodeDay\Sponsor', \TinyDb\Sql::create()
                                    ->select('*')
                                    ->from(CodeDay\Sponsor::$table_name)
                                    ->where('eventID = ?', $this->eventID)
                                    ->order_by('sort ASC'));
    }

    public function __get_full_schedule()
    {
        return new \TinyDb\Collection('\StudentRND\My\Models\CodeDay\Schedule', \TinyDb\Sql::create()
                                    ->select('*')
                                    ->from(CodeDay\Schedule::$table_name)
                                    ->where('eventID = ?', $this->eventID)
                                    ->order_by('date ASC'));
    }

    public function __get_pre_event_workshops()
    {
        $time = $this->start_date;
        return $this->get_schedule('workshop')->filter(function($schedule) use($time){
            return $schedule->date < $time;
        });
    }

    public function __get_event_workshops()
    {
        $time = $this->start_date;
        return $this->get_schedule('workshop')->filter(function($schedule) use($time){
            return $schedule->date >= $time;
        });
    }

    /**
     * Gets a schedule
     * @param  string $type 'schedule', 'workshop'
     */
    public function get_schedule($type)
    {
        return new \TinyDb\Collection('\StudentRND\My\Models\CodeDay\Schedule', \TinyDb\Sql::create()
                                    ->select('*')
                                    ->from(CodeDay\Schedule::$table_name)
                                    ->where('eventID = ?', $this->eventID)
                                    ->where('type = ?', $type)
                                    ->order_by('date ASC'));
    }

}
