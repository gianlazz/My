<?php

namespace StudentRND\My\Controllers;

use \StudentRND\My\Models;

class user extends \CuteControllers\Base\Rest
{
    public function before()
    {
        $this->group = NULL;
        if ($this->request->get('group') !== NULL) {
            $this->group = new \StudentRND\My\Models\Group($this->request->get('group'));
        }

        $this->user = \StudentRND\My\Models\User::current();
    }

    public function get_index()
    {

        $query = \TinyDb\Sql::create()
                  ->select('*')
                  ->from(\StudentRND\My\Models\Group::$table_name);
        if (!$this->user->is_admin) {
            $query = $query->where("type = 'open' OR type = 'closed'");
        }

        $visible_groups = new \TinyDb\Collection('\StudentRND\My\Models\Group', $query);

        include(TEMPLATE_DIR . '/Home/groups/index.php');
    }

    public function get_page()
    {
        if (!isset($this->group) ||
            ($this->group->type === 'secret' && !$this->user->is_admin) ||
            ($this->group->type === 'private' && !$this->user->has_group($this->group) && !$this->user->is_admin)) {
            throw new \CuteControllers\HttpError(404);
        }

        if ($this->user->has_group($this->group)) {
            include(TEMPLATE_DIR . '/Home/groups/page_member.php');
        } else {
            include(TEMPLATE_DIR . '/Home/groups/page_member.php');
        }
    }
}
