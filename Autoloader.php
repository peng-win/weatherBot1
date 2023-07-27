<?php

class Autoloader
{
    //private const SRC_PATH = './app/';

    public static function register()
    {
        spl_autoload_register(static function ($class){
            $file = __DIR__."/app/{$class}.php";
            if (file_exists($file)){
                require_once $file;
                return true;
            }
        });
    }

}

Autoloader::register();
