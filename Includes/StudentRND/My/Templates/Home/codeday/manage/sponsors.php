<?php

namespace StudentRND\My\Controllers\CodeDay\Manage;

use \StudentRND\My\Models\CodeDay;

class Sponsors extends \CuteControllers\Base\Rest
{
    public function before()
    {
        $this->user = \StudentRND\My\Models\User::current();
        if (!\StudentRND\My\Models\User::current()->has_group(new \StudentRND\My\Models\Group(8))) {
            throw new \CuteControllers\HttpError(403);
        }

        $this->event = new CodeDay\Event($this->request->request('eventID'));

        if ($this->request->request('sponsorID')) {
            $this->sponsor = new CodeDay\Sponsor($this->request->request('sponsorID'));
        }

        $this->sponsor_form = new \FastForms\Form('\StudentRND\My\Models\CodeDay\Sponsor');
    }

    public function get_create()
    {
        include_once(TEMPLATE_DIR . '/Home/codeday/manage/sponsors.php');
    }

    public function get_update()
    {
        include_once(TEMPLATE_DIR . '/Home/codeday/manage/sponsors.php');
    }

    public function post_update()
    {
        try {
            $this->sponsor_form->update($this->sponsor);
        } catch (\Exception $ex) {
            $error = $ex->getMessage();
            include_once(TEMPLATE_DIR . '/Home/codeday/create.php');
            return;
        }

        $this->redirect('/codeday/admin/update?eventId=' . $this->event->eventID);
    }

    public function post_create()
    {
        try {
            $this->event_form->create();
        } catch (\Exception $ex) {
            $error = $ex->getMessage();
            include_once(TEMPLATE_DIR . '/Home/codeday/create.php');
            return;
        }

        $this->redirect('/codeday/admin/update?eventId=' . $this->event->eventID);
    }
}
