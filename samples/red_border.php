<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/icecream.jpg');

$image->fx('resize', 196)
      ->fx('crop', 166, 70)
      ->fx('corners', 15, 15)
      ->draw('border', 5, 'FF0000')
      ->fx('corners', 17, 17)
      ->draw('border', 5, 'FF8888')
      ->fx('corners', 20, 20)
      ->draw('border', 5, 'FFCCCC')
      ->fx('corners', 22, 22);

$image->imagePng();
