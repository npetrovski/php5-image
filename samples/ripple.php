<?php

require_once dirname(__FILE__) . '/../autoload.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/field.jpg');

$image->fx('resize', 200)
      ->fx('crop', 0, 90)
      ->fx('ripple')
      ->fx('corners', 13, 13)
      ->draw('border', 5, 'FFFFFF')
      ->fx('corners', 15, 15);

$image->imagePng();
