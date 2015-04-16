<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/beach.jpg');

$image->fx('resize', 200)
      ->fx('crop', 0, 86)
      ->draw('border', 2, '000000')
      ->fx('canvassize', 0, 10, 10, 0)
      ->draw('watermark', dirname(__FILE__) . '/source/button.png', 'br');

$image->imagePng();
