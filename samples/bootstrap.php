<?php
ini_set("memory_limit", "512M");

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    dirname(__FILE__). '/../src',
    get_include_path(),
)));

// set up an autoload for Zend / Pear style class loading
spl_autoload_register(function ($class) {
    if (0 === strpos($class, 'Image')) {
        $class = str_replace('\\', '/', $class);
        require($class . '.php');
    }
});

