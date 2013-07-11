<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image(dirname(__FILE__) . '/source/road.jpg');

$image->attach(new Image_Fx_Resize(250));
$image->attach(new Image_Fx_Crop(196, 70));
$image->attach(new Image_Draw_Infobar("[Filename]", "t", "c", "FFFFBB", "000000"));
$image->attach(new Image_Fx_Corners(10,10));
$image->attach(new Image_Draw_Border(5, "000000"));
$image->attach(new Image_Fx_Corners(12,12));

$image->imagePng();
