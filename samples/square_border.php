<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/car.jpg');

$image->attach(new Image\Fx\Resize(198));
$image->attach(new Image\Fx\Crop(156, 60));
$image->attach(new Image\Fx\Blackandwhite());

$image->attach(new Image\Draw\Border(2, "000000"));
$image->attach(new Image\Draw\Border(1, "FFFFFF"));
$image->attach(new Image\Draw\Border(17, "000000"));

$image->imagePng();
