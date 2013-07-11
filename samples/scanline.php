<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image(dirname(__FILE__) . '/source/quay.jpg');

$image->attach(new Image_Fx_Resize(250));
$image->attach(new Image_Fx_Crop(206, 100));

$image->attach(new Image_Draw_Scanline(2, "FFFFFF", 120, 110));

$image->attach(new Image_Fx_Corners(15,15));

$image->imagePng();
