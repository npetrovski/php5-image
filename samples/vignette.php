<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image(dirname(__FILE__) . '/source/balloons.jpg');

$image->attach(new Image_Fx_Resize(250));
$image->attach(new Image_Fx_Crop(206, 100));
$image->attach(new Image_Fx_Vignette(new Image_Image(dirname(__FILE__) . '/source/vignette.png')));

$image->imagePng();
