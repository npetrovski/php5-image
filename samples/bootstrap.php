<?php
ini_set("memory_limit", "128M");

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    dirname(__FILE__). '/../src',
    get_include_path(),
)));

require 'Image\Autoloader.php';

Image\Autoloader::register();

