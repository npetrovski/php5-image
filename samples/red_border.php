<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image(dirname(__FILE__) . '/source/icecream.jpg');


$image->attach(new Image_Fx_Resize(196));
$image->attach(new Image_Fx_Crop(166,70));

$image->attach(new Image_Fx_Corners(15,15));

$image->attach(new Image_Draw_Border(5, "FF0000"));
$image->attach(new Image_Fx_Corners(17,17));

$image->attach(new Image_Draw_Border(5, "FF8888"));
$image->attach(new Image_Fx_Corners(20,20));

$image->attach(new Image_Draw_Border(5, "FFCCCC"));
$image->attach(new Image_Fx_Corners(22,22));

$image->imagePng();
