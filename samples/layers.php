<?php

require_once dirname(__FILE__) . '/../_autoload.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/rose.jpg');
$layer = new Image\Canvas(dirname(__FILE__) . '/source/cherry.png');

$image->fx('corners', 15, 15)
      ->fx('resize', 198)
      ->fx('crop', 196, 96)
      ->draw('layer', $layer, 0, 0, false);

$image->imagePng();
