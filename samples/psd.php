<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Base(dirname(__FILE__) . '/source/iPod.psd');

$image->fx('resize', 196)
      ->fx('crop', 0, 100);

$image->imagePng();
