<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/reflect.jpg');

$image->fx('resize', 206)
      ->fx('crop', 194, 88)
      ->fx('corners', 15, 15)
      ->draw('trueshadow', 5, "444444", array(1, 1, 1, 2, 2, 4, 4, 8, 4, 4, 2, 2, 1, 1, 1));

$image->imagePng();
