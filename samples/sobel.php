<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image(dirname(__FILE__) . '/source/flowers.jpg');

$image->attach(new Image_Fx_Resize(196));
$image->attach(new Image_Fx_Crop(0,100));
$image->attach(new Image_Fx_Sobel());

$image->imagePng();
