<?php

namespace StudentRND\My\Controllers\CodeDay;

class index extends \CuteControllers\Base\Web
{
    public function __index()
    {
        $events = new \TinyDb\Collection('\StudentRND\My\Models\CodeDay\Event', \TinyDb\Sql::create()
                                         ->select('*')
                                         ->from(\StudentRND\My\Models\CodeDay\Event::$table_name));
        include_once(TEMPLATE_DIR . '/Home/codeday/list.php');
    }
}
