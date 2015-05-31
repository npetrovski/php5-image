<?php

require_once dirname(__FILE__) . '/../_autoload.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/iPodnanoorange.ico');

$image->fx('resize', 196)
      ->fx('crop', 0, 100);

$image->imagePng();
