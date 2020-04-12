<?php
namespace Lib\Net;

class Prowl
{
    public function alert($apikey, $application, $subject, $body, $priority=0)
    {
        (new Http)->post('https://api.prowlapp.com/publicapi/add', [
            'apikey' => $apikey,
            'priority' => $priority,
            'application' => $application,
            'event' => $subject,
            'description' => $body
        ]);
    }
}
