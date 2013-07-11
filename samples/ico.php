<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image(dirname(__FILE__) . '/source/iPodnanoorange.ico');

$image->attach(new Image_Fx_Resize(196));
$image->attach(new Image_Fx_Crop(0,100));

$image->imagePng();
