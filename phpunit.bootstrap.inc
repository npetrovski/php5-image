<?php

defined('TEST_BASE') || define('TEST_BASE', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'tests'));

// set error reporting high
error_reporting(E_ALL | E_STRICT);

// make sure we see them
ini_set('display_errors', 'On');

// make sure current directory and class directories are on include path
// this is necessary for auto load to work
set_include_path(
        // distribution files (where the zip / tgz is unpacked)
        dirname(__FILE__) . PATH_SEPARATOR .
        // PHP5 Image Library
        dirname(__FILE__) . DIRECTORY_SEPARATOR . 'src' . PATH_SEPARATOR .
        // test file directory "tests"
        dirname(__FILE__) . DIRECTORY_SEPARATOR . 'tests' . PATH_SEPARATOR .
        // current include path (for PHPUnit, etc.)
        get_include_path()
);

function _autoload($class) {
    if (0 === strpos($class, 'Image')) {
        $class = str_replace('\\', '/', $class);
        require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $class . '.php');
    }
}

// set up an autoload for Zend / Pear style class loading
spl_autoload_register('_autoload');
