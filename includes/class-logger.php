<?php

namespace WPUniversalWebhooks;

class Logger {

    public static function log($event,$payload,$url){

        $logs = get_option('wpunw_logs',[]);

        $logs[] = [
            'time'=>current_time('mysql'),
            'event'=>$event,
            'url'=>$url,
            'payload'=>$payload
        ];

        if(count($logs) > 200){
            $logs = array_slice($logs,-200);
        }

        update_option('wpunw_logs',$logs);

    }

}