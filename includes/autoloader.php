<?php

namespace WPUniversalWebhooks;

class Autoloader {

    public static function register(){

        spl_autoload_register([self::class,'autoload']);

    }

    private static function autoload($class){

        if(strpos($class,'WPUniversalWebhooks\\') !== 0){
            return;
        }

        $class = str_replace('WPUniversalWebhooks\\','',$class);

        $file = WPUNW_PATH.'includes/class-'.strtolower($class).'.php';

        if(file_exists($file)){
            require $file;
        }

    }

}