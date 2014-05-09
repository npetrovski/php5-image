<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Base();
$image->createImageTrueColorTransparent(192, 96);
$image->attach(new Image\Fx\Canvassize(0, 0, 0, 0, "FFFFFF"));
$image->attach(new Image\Draw\Border(1, "BBBBBB"));
$image->attach(new Image\Draw\Border(1, "FFFFFF"));

$image->imagePng();
