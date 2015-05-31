<?php

require_once dirname(__FILE__) . '/../_autoload.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/road.jpg');

$image->fx('resize', 250)
        ->fx('crop', 196, 70)
        ->draw('infobar', '[Filename]', 't', 'c', 'FFFFBB', '000000')
        ->fx('corners', 10, 10)
        ->draw('border', 5, '000000')
        ->fx('corners', 12, 12);

$image->imagePng();
