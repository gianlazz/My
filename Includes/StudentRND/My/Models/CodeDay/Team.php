<?php

namespace StudentRND\My\Models\CodeDay;

use \StudentRND\My\Models\CodeDay;

class Team extends \TinyDb\Orm
{
    public static $primary_key = "teamID";
    public static $table_name = "codeday_teams";

    protected $teamID;
    protected $name;
    protected $image;
    protected $presentation_link;
    protected $game_link;

    protected $eventID;

    public static function create($title, $image, $html, CodeDay\Event $event)
    {
        return parent::create(array(
                              'title' => $title,
                              'image' => $image,
                              'html' => $html,
                              'sort' => 1000,
                              'eventID' => $event->eventID));
    }

    public function __get_event()
    {
        return new CodeDay\Event($this->eventID);
    }
}
