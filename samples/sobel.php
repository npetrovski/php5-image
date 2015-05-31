<?php

require_once dirname(__FILE__) . '/../autoload.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/flowers.jpg');

$image->fx('resize', 196)
      ->fx('crop', 0, 100)
      ->fx('sobel');

$image->imagePng();
