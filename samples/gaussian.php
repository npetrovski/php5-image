<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image(dirname(__FILE__) . '/source/seats.jpg');

$image->attach(new Image_Fx_Resize(250));
$image->attach(new Image_Fx_Crop(206, 96));
$image->attach(new Image_Fx_Gaussian());
$image->attach(new Image_Draw_Border(1, "BBBBBB"));
$image->attach(new Image_Draw_Border(1, "FFFFFF"));

$image->imagePng();
