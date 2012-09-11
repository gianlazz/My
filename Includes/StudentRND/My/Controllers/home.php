<?php

namespace StudentRND\My\Controllers;

class index extends \CuteControllers\Base\Rest
{
    public function before()
    {
        $this->user = \StudentRND\My\Models\User::current();
    }
    public function get_index()
    {
        include(TEMPLATE_DIR . '/Home/index.php');
    }
}
