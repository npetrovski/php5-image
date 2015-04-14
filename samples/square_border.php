<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/car.jpg');

$image->fx('resize', 198)
      ->fx('crop', 156, 60)
      ->fx('blackandwhite')
      ->draw('border', 2, '000000')
      ->draw('border', 1, 'ffffff')
      ->draw('border', 17, '000000');

$image->imagePng();
