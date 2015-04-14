<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/seats.jpg');

$image->fx('resize', 250)
      ->fx('crop', 206, 96)
      ->fx('gaussian')
      ->draw('border', 1, 'BBBBBB')
      ->draw('border', 1, 'FFFFFF');

$image->imagePng();
