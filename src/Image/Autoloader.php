<?php

namespace Image;

class Autoloader {

    public static function register() {
        spl_autoload_register(array(new self, 'load'));
    }

    public static function load($class) { 
        if (0 === strpos($class, 'Image')) {

            $class = str_replace(
                array('Image', '\\'), array('', '/'), $class
            );

            $file = dirname(__FILE__) . $class . '.php';

            require($file);
        }
    }

}
