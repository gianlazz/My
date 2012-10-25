<?php

namespace StudentRND\My\Controllers\user;

use \StudentRND\My\Models;

class call extends \CuteControllers\Base\Rest
{
    public function before()
    {
        $this->user = new Models\User(array('username' => $this->request->request('username')));
    }

    public function __get_index()
    {
        include(TEMPLATE_DIR . '/Home/user/call.php');
    }

    public function __post_index()
    {
        if ($this->user->phone && Models\User::current()->phone) {
            try {
                global $config;
                \StudentRND\My\Twilio::$client->account->calls->create(
                    \StudentRND\My\Twilio::$number,
                    Models\User::current()->plain_phone,
                    \CuteControllers\Router::link('/user/call/connect?username=' . $this->user->username, TRUE)
                );

                $info = 'Calling you...';
            } catch (\Exception $ex) {
                $error = $ex->getMessage();
            }
            include(TEMPLATE_DIR . '/Home/error.php');
        } else {
            $error = 'No phone number.';
            include(TEMPLATE_DIR . '/Home/error.php');
        }
    }

    public function __post_connect()
    {
        global $config;
        if ($this->request->post('AccountSid') !== $config['twilio']['sid']) {
            throw new \CuteControllers\HttpError(401);
        }

        header("content-type: text/xml");
        echo <<<END
<?xml version="1.0" encoding="UTF-8"?>
<Response>
    <Say>Now connecting you to {$this->user->name}</Say>
    <Dial>+{$this->user->plain_phone}</Dial>
    <Say>The call has ended. Goodbye.</Say>
</Response>
END;
        exit;
    }
}
