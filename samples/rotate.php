<?php

require_once dirname(__FILE__) . '/../autoload.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/apple.png');

$image->fx('rotate', 35)
      ->fx('flip', true, true)
      ->fx('resize', 100);

$image->imagePng();
