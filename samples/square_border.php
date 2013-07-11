<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image(dirname(__FILE__) . '/source/car.jpg');

$image->attach(new Image_Fx_Resize(198));
$image->attach(new Image_Fx_Crop(156,60));
$image->attach(new Image_Fx_Blackandwhite());

$image->attach(new Image_Draw_Border(2, "000000"));
$image->attach(new Image_Draw_Border(1, "FFFFFF"));
$image->attach(new Image_Draw_Border(17, "000000"));

$image->imagePng();
