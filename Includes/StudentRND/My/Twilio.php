<?php

namespace StudentRND\My;

class Twilio
{
    public static $client;
    public static $number;
}

global $config;
Twilio::$number = $config['twilio']['number'];
Twilio::$client = new \Services_Twilio($config['twilio']['sid'], $config['twilio']['token'], '2010-04-01');
