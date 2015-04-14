<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/quay.jpg');

$image->attach(new Image\Fx\Resize(250));
$image->attach(new Image\Fx\Crop(206, 100));

$image->attach(new Image\Draw\Scanline(2, "FFFFFF", 120, 110));

$image->attach(new Image\Fx\Corners(15, 15));

$image->imagePng();
