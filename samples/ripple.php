<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image(dirname(__FILE__) . '/source/field.jpg');

$image->attach(new Image_Fx_Resize(200));
$image->attach(new Image_Fx_Crop(0, 90));

$image->attach(new Image_Fx_Ripple());
$image->attach(new Image_Fx_Corners(13,13));
$image->attach(new Image_Draw_Border(5, "FFFFFF"));
$image->attach(new Image_Fx_Corners(15,15));

$image->imagePng();
