<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image(dirname(__FILE__) . '/source/red.jpg');

$image->attach(new Image_Fx_Resize(196));
$image->attach(new Image_Fx_Crop(0,100));
$image->attach(new Image_Fx_Colorize('ff0000', '00ff00'));

$image->imagePng();
