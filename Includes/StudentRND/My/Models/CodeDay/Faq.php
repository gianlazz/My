<?php

namespace StudentRND\My\Models\CodeDay;

use \StudentRND\My\Models\CodeDay;

class Faq extends \TinyDb\Orm
{
    public static $table_name = "codeday_faqs";
    public static $primary_key = "faqID";

    protected $faqID;
    protected $question;
    protected $answer;

    protected $eventID;

    public function __get_event()
    {
        return new CodeDay\Event($this->eventID);
    }
}
