<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/boat.jpg');

$image->attach(new Image\Fx\Resize(198));
$image->attach(new Image\Fx\Crop(196, 96));

$wm_image = new Image\Canvas(dirname(__FILE__) . '/source/phpimage.png');
$wm_image->mid_handle = false;

$watermark = new Image\Draw\Watermark($wm_image);
$watermark->setPosition(0, 60);

$image->attach($watermark);
$image->attach(new Image\Draw\Border(2, "000000"));

$image->imagePng();
