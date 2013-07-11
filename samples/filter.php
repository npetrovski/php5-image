<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image(dirname(__FILE__) . '/source/face.jpg');
$image->attach(new Image_Fx_Resize(198));
$image->attach(new Image_Fx_Crop(194,94));

$image->attach(new Image_Fx_Filter(IMG_FILTER_NEGATE));
$image->attach(new Image_Draw_Border(1, "FFFFFF"));
$image->attach(new Image_Draw_Border(1, "BBBBBB"));
$image->attach(new Image_Draw_Border(1, "FFFFFF"));

$image->imagePng();
