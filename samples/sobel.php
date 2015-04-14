<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/flowers.jpg');

$image->attach(new Image\Fx\Resize(196));
$image->attach(new Image\Fx\Crop(0, 100));
$image->attach(new Image\Fx\Sobel());

$image->imagePng();
