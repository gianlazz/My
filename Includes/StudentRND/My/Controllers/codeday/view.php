<?php

namespace StudentRND\My\Controllers\CodeDay;

use \StudentRND\My\Models\CodeDay;

class view extends \CuteControllers\Base\Web
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

        $name = $this->request->request('name');
        $year = $this->request->request('year');
        $month = $this->request->request('month');

        $this->current_codeday = $this->all_codedays->find_one(function($event) use ($month, $year, $name) {
            if (strtolower($name) != strtolower($event->name)) {
                return FALSE;
            }else if ($month && $year) {
                return (strtolower(date('M Y', $event->start_date)) == strtolower("$month $year"));
            } else {
                return TRUE;
            }
        });

        if (!$this->current_codeday) {
            require_once(TEMPLATE_DIR . '/CodeDay/404.php');
            exit;
        }
    }

    public function rules()
    {
        require_once(TEMPLATE_DIR . '/CodeDay/rules.php');
    }

    public function index()
    {
        require_once(TEMPLATE_DIR . '/CodeDay/index.php');
    }

    public function schedule()
    {
        require_once(TEMPLATE_DIR . '/CodeDay/schedule.php');
    }

    public function register()
    {
        require_once(TEMPLATE_DIR . '/CodeDay/register.php');
    }

    public function faq()
    {
        require_once(TEMPLATE_DIR . '/CodeDay/faq.php');
    }
}
