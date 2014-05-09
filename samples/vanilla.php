<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Base(dirname(__FILE__) . '/source/hotel.jpg');

$image->fx('resize', 200)
        ->fx('crop', 0, 94)
        ->draw('border', 1, 'FFFFFF')
        ->draw('border', 1, 'BBBBBB')
        ->draw('border', 1, 'FFFFFF');

$image->imagePng();
