<?php

namespace StudentRND\My;

class Util
{
    public static function get_gapps_api_client(){
        global $config;
        require_once(INCLUDES_DIR . '/Zend/Loader.php');
        \Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
        \Zend_Loader::loadClass('Zend_Gdata_Gapps');

        $loginToken = null;
        $loginCaptcha = null;

        $client = \Zend_Gdata_ClientLogin::getHttpClient($config['google']['account'],
                                                        $config['google']['password'],
                                                        \Zend_Gdata_Gapps::AUTH_SERVICE_NAME,
                                                        null,
                                                        '',
                                                        $loginToken,
                                                        $loginCaptcha,
                                                        \Zend_Gdata_ClientLogin::CLIENTLOGIN_URI, 'HOSTED');
        $service = new \Zend_Gdata_Gapps($client, $config['app']['domain']);

        return $service;
    }
}
