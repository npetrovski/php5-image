<?php

require_once dirname(__FILE__) . '/../autoload.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/planes.jpg');

$image->fx('resize', 250)
        ->fx('crop', 206, 96)
        ->fx('jitter')
        ->draw('border', 1, 'BBBBBB')
        ->draw('border', 1, 'FFFFFF');

$image->imagePng();
