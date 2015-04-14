<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/face.jpg');

$image->fx('resize', 198)
      ->fx('crop', 124, 94)
      ->fx('filter', IMG_FILTER_NEGATE)
      ->draw('border', 1, 'FFFFFF')
      ->draw('border', 1, 'BBBBBB')
      ->draw('border', 1, 'FFFFFF');

$image->imagePng();
