<?php

namespace StudentRND\My\Controllers;

class home extends \CuteControllers\Base\Web
{
    public function before()
    {
        $this->user = \StudentRND\My\Models\User::current();
    }

    public function __index()
    {
        include(TEMPLATE_DIR . '/Home/index.php');
    }
}
