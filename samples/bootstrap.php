<?php
ini_set("memory_limit", "128M");

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    dirname(__FILE__). '/../src',
    get_include_path(),
)));

function _autoload($class) {
    require_once str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
}

spl_autoload_register('_autoload');