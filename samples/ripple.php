<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/field.jpg');

$image->attach(new Image\Fx\Resize(200));
$image->attach(new Image\Fx\Crop(0, 90));

$image->attach(new Image\Fx\Ripple());
$image->attach(new Image\Fx\Corners(13, 13));
$image->attach(new Image\Draw\Border(5, "FFFFFF"));
$image->attach(new Image\Fx\Corners(15, 15));

$image->imagePng();
