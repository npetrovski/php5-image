<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Base(dirname(__FILE__) . '/source/icecream.jpg');

$image->attach(new Image\Fx\Resize(196));
$image->attach(new Image\Fx\Crop(166, 70));

$image->attach(new Image\Fx\Corners(15, 15));

$image->attach(new Image\Draw\Border(5, "FF0000"));
$image->attach(new Image\Fx\Corners(17, 17));

$image->attach(new Image\Draw\Border(5, "FF8888"));
$image->attach(new Image\Fx\Corners(20, 20));

$image->attach(new Image\Draw\Border(5, "FFCCCC"));
$image->attach(new Image\Fx\Corners(22, 22));

$image->imagePng();
