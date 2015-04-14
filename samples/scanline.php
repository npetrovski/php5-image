<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/quay.jpg');

$image->fx('resize', 250)
      ->fx('crop', 206, 100)
      ->draw('scanline', 2, 'FFFFFF', 120, 110)
      ->fx('corners', 15, 15);

$image->imagePng();
