<?php

namespace StudentRND\My\Controllers\CodeDay;

use \StudentRND\My\Models\CodeDay;

class Admin extends \CuteControllers\Base\CrudFormController
{
    public static $template = '';
    public static $model = '\StudentRND\My\Models\CodeDay\Event';

    public function before()
    {
        $this->user = \StudentRND\My\Models\User::current();
        if (!$this->user->is_admin) {
            throw new \CuteControllers\HttpError(403);
        }

        parent::before();
    }
}

Admin::$template = TEMPLATE_DIR . '/Home/form-page.php';
