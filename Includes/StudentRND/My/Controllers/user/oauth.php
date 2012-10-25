<?php

namespace StudentRND\My\Controllers\user;

use \StudentRND\My\Models;

class oauth extends \CuteControllers\Base\Rest
{
    public function before()
    {
        $this->user = Models\User::current();
    }

    public function __get_index()
    {
        global $config;
        $service = $this->request->get('service');

        $redirect_uri = \CuteControllers\Router::link('/user/oauth/callback?service=' . $service, TRUE);
        $redirect_uri = urlencode($redirect_uri);

        switch($service) {
            case "fb":
                $client_id = $config['fb']['client_id'];
                $url = "https://www.facebook.com/dialog/oauth/?client_id=$client_id&redirect_uri=$redirect_uri&scope=read_friendlists";
                $this->redirect($url);
                exit;
                break;
            default:
                throw new \CuteControllers\HttpError(404);
        }
    }

    public function __get_break()
    {
        $service = $this->request->get('service');

        switch($service) {
            case "fb":
                $this->user->fb_id = NULL;
                $this->user->fb_access_token = NULL;
                break;
            default:
                throw new \CuteControllers\HttpError(404);
        }

        $this->user->update();

        $this->redirect('/user');
    }

    public function __get_callback()
    {
        global $config;

        $service = $this->request->get('service');
        $redirect_uri = \CuteControllers\Router::link('/user/oauth/callback?service=' . $service, TRUE);

        switch($service) {
            case "fb":
                $code = $this->request->get('code');
                $client_id = $config['fb']['client_id'];
                $client_secret = $config['fb']['client_secret'];

                $url = "https://graph.facebook.com/oauth/access_token?client_id=$client_id&client_secret=$client_secret&code=$code&redirect_uri=$redirect_uri";
                $response = file_get_contents($url);
                $params = null;
                parse_str($response, $params);

                $this->user->fb_access_token = $params['access_token'];

                $graph_url = "https://graph.facebook.com/me?access_token=" . $params['access_token'];
                $fb_user = json_decode(file_get_contents($graph_url));
                $this->user->fb_id = $fb_user->id;
                break;
            default:
                throw new \CuteControllers\HttpError(404);
        }


        $this->user->update();
        $this->redirect('/user');
    }
}
