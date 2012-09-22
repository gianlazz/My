<?php

namespace StudentRND\My\Models\CodeDay;

use \StudentRND\My\Models\CodeDay;

class Block extends \TinyDb\Orm
{
    public static $table_name = "codeday_blocks";
    public static $primary_key = "blockID";

    protected $blockID;
    protected $title;

    public static $__class_image = 'image-picker';
    protected $image;

    public static $__name_html = 'Block HTML';
    public static $__class_html = 'html-editor input-xxlarge';
    protected $html;
    protected $sort;

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
