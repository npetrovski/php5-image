<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Canvas(200, 100, 'CCCCDD', 60);

$star = new Image\Canvas(dirname(__FILE__) . '/source/star.png');
$star->draw('border', 1, '000000');

$trim = new Image\Canvas(dirname(__FILE__) . '/source/star.png');
$trim->fx('trim')
     ->draw('border', 1, '000000');

$image->fx('colorize', 'ff0000')
      ->draw('layer', $star, 20, 20)
      ->draw('layer', $trim, 100, 20);


$image->imagePng();
