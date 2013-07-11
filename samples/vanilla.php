<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image(dirname(__FILE__) . '/source/hotel.jpg');

$image->attach(new Image_Fx_Resize(200));
$image->attach(new Image_Fx_Crop(0, 94));
$image->attach(new Image_Draw_Border(1, "FFFFFF"));
$image->attach(new Image_Draw_Border(1, "BBBBBB"));
$image->attach(new Image_Draw_Border(1, "FFFFFF"));

$image->imagePng();
