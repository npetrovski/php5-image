<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image(dirname(__FILE__) . '/source/beach.jpg');

    
$image->attach(new Image_Fx_Resize(200));
$image->attach(new Image_Fx_Crop(0, 86));
$image->attach(new Image_Draw_Border(2, "000000"));
$image->attach(new Image_Fx_Canvassize(0, 10, 10, 0));

$watermark = new Image_Draw_Watermark(new Image_Image(dirname(__FILE__) . '/source/button.png'));
$watermark->setPosition("br");
$image->attach($watermark);

$image->imagePng();
