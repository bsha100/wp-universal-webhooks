<?php

namespace WPUniversalWebhooks;

class Logger {

    public static function log($event, $payload, $url){

        $logs = get_option('wpunw_logs', []);
        $logs[] = [
            'time' => current_time('mysql'),
            'event' => $event,
            'url' => $url,
            'payload' => $payload
        ];

        update_option('wpunw_logs', $logs);

    }

}