<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image(dirname(__FILE__) . '/source/stamens.jpg');


$image->attach(new Image_Fx_Resize(196));
$image->attach(new Image_Fx_Crop(0,96));

$watermark = new Image_Draw_Watermark(new Image_Image(dirname(__FILE__) . '/source/watermark.png'));
$watermark->setPosition("tile");

$image->attach($watermark);

$image->attach(new Image_Draw_Border(2, "000000"));

$image->imagePng();
