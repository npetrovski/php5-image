<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Base(dirname(__FILE__) . '/source/reflect.jpg');

$image->attach(new Image\Fx\Resize(206));
$image->attach(new Image\Fx\Crop(194, 88));

$image->attach(new Image\Fx\Corners(15, 15));

$image->attach(new Image\Draw\Trueshadow(5, "444444", array(1, 1, 1, 2, 2, 4, 4, 8, 4, 4, 2, 2, 1, 1, 1)));

$image->imagePng();
