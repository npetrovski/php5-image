<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image(dirname(__FILE__) . '/source/portrait.jpg');

$image->attach(new Image_Fx_Resize(198));
$image->attach(new Image_Fx_Crop(196,96));


$image->attach(new Image_Helper_FaceDetector());

$image->evaluateFXStack()->drawFaceRectangle();

$image->imagePng();

