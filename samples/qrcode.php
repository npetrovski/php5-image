<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image_Image(250, 100);

$image->attach(new Image_Draw_QRCode("http://code.google.com/p/php5-image/"));

$image->imagePng();
