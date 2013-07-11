<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image(dirname(__FILE__) . '/source/boat.jpg');

$image->attach(new Image_Fx_Resize(198));
$image->attach(new Image_Fx_Crop(196,96));

$wm_image = new Image_Image(dirname(__FILE__) . '/source/phpimage.png');
$wm_image->mid_handle = false;

$watermark = new Image_Draw_Watermark($wm_image);
$watermark->setPosition(0,60);

$image->attach($watermark);
$image->attach(new Image_Draw_Border(2, "000000"));

$image->imagePng();
