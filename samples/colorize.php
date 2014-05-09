<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Base(dirname(__FILE__) . '/source/red.jpg');

$image->fx('resize', 196)
      ->fx('crop', 0, 100)
      ->fx('colorize', 'ff0000', '00ff00');

$image->imagePng();
