<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image(dirname(__FILE__) . '/source/rose.jpg');



$secondImage = new Image_Image(dirname(__FILE__) . '/source/cherry.png');

$layer = new Image_Draw_Layer($secondImage, false);
$layer->setPosition(0, 0);

$image->attach(new Image_Fx_Corners(15,15));
$image->attach(new Image_Fx_Resize(198));
$image->attach(new Image_Fx_Crop(196,96));

$image->attach($layer);

$image->imagePng();
