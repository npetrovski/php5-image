<?php

require_once dirname(__FILE__) . '/../_autoload.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/boat.jpg');
$image->fx('resize', 198)
      ->fx('crop', 196, 96);
      
$wm_image = new Image\Canvas(dirname(__FILE__) . '/source/phpimage.png');
$wm_image->mid_handle = false;

$watermark = new Image\Draw\Watermark($wm_image);
$watermark->setPosition(0, 60);

$image->attach($watermark);
$image->draw('border', 2, "000000");

$image->imagePng();
