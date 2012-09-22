<?php

namespace StudentRND\My\Controllers\codeday\manage;

use \StudentRND\My\Models\CodeDay;

class Index extends \CuteControllers\Base\Web
{
    public function index()
    {
        $event = new CodeDay\Event($this->request->request('eventID'));
        require(TEMPLATE_DIR . '/Home/codeday/manage/index.php');
    }
}
