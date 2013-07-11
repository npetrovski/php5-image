<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image(dirname(__FILE__) . '/source/reflect.jpg');

$image->attach(new Image_Fx_Resize(206));
$image->attach(new Image_Fx_Crop(194, 88));

$image->attach(new Image_Fx_Corners(15,15));

$image->attach(new Image_Draw_Trueshadow(5, "444444", array(1,1,1,2,2,4,4,8,4,4,2,2,1,1,1)));

$image->imagePng();
