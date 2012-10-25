<?php

namespace StudentRND\My\Controllers\CodeDay;

use \StudentRND\My\Models\CodeDay;

class globalController extends \CuteControllers\Base\Web
{
    public function before()
    {
        $this->all_codedays = new \TinyDb\Collection('\StudentRND\My\Models\CodeDay\Event', \TinyDb\Sql::create()
                                                     ->select('*')
                                                     ->from(CodeDay\Event::$table_name)
                                                     ->order_by('`end_date` DESC'));

        $this->upcoming_codedays = new \TinyDb\Collection('\StudentRND\My\Models\CodeDay\Event', \TinyDb\Sql::create()
                                                     ->select('*')
                                                     ->from(CodeDay\Event::$table_name)
                                                     ->where('`end_date` > NOW()')
                                                     ->order_by('`start_date` ASC'));

        $this->past_codedays = new \TinyDb\Collection('\StudentRND\My\Models\CodeDay\Event', \TinyDb\Sql::create()
                                                     ->select('*')
                                                     ->from(CodeDay\Event::$table_name)
                                                     ->where('`end_date` < NOW()')
                                                     ->order_by('`end_date` DESC'));
    }

    public function __index()
    {
        echo "<h1>Upcoming CodeDays</h1>";
        foreach ($this->upcoming_codedays as $codeday) {
            echo '<a href="http://' . strtolower($codeday->name) . '.codeday.org/">' . $codeday->name . '</a><br />';
        }
        //require_once(TEMPLATE_DIR . '/CodeDay/index.php');
    }
}
