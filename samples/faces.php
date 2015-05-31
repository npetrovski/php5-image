<?php

require_once dirname(__FILE__) . '/../_autoload.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/portrait.jpg');

$image->fx('resize', 198)
      ->fx('crop', 196, 96)
      ->helper('facedetector')
      ->apply()
      ->drawFaceRectangle();

$image->imagePng();

