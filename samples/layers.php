<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Base(dirname(__FILE__) . '/source/rose.jpg');

$secondImage = new Image\Base(dirname(__FILE__) . '/source/cherry.png');

$layer = new Image\Draw\Layer($secondImage, false);
$layer->setPosition(0, 0);

$image->attach(new Image\Fx\Corners(15, 15));
$image->attach(new Image\Fx\Resize(198));
$image->attach(new Image\Fx\Crop(196, 96));

$image->attach($layer);

$image->imagePng();
