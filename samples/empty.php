<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image();
$image->createImageTrueColorTransparent(192,96);
$image->attach(new Image_Fx_Canvassize(0, 0, 0, 0, "FFFFFF"));
$image->attach(new Image_Draw_Border(1, "BBBBBB"));
$image->attach(new Image_Draw_Border(1, "FFFFFF"));

$image->imagePng();
