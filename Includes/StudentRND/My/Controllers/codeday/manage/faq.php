<?php

namespace StudentRND\My\Controllers\CodeDay;

use \StudentRND\My\Models\CodeDay;

class Faq extends \CuteControllers\Base\CrudFormController
{
    public static $template = '';
    public static $model = '\StudentRND\My\Models\CodeDay\Faq';
    public $static_values = array();

    public function before()
    {
        $this->user = \StudentRND\My\Models\User::current();
        if (!\StudentRND\My\Models\User::current()->has_group(new \StudentRND\My\Models\Group(8))) {
            throw new \CuteControllers\HttpError(403);
        }

        $this->static_values['eventID'] = $this->request->request('eventID');

        parent::before();
    }
}

Faq::$template = TEMPLATE_DIR . '/Home/codeday/form.php';
