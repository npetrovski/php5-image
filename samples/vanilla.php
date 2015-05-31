<?php

require_once dirname(__FILE__) . '/../_autoload.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/hotel.jpg');

$image->fx('resize', 200)
      ->fx('crop', 0, 94)
      ->draw('border', 1, 'FFFFFF')
      ->draw('border', 1, 'BBBBBB')
      ->draw('border', 1, 'FFFFFF');

$image->imagePng();
