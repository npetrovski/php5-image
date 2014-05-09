<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Base(dirname(__FILE__) . '/source/balloons.jpg');


$image->fx('resize', 250)
        ->fx('crop', 206, 100)
        ->fx('vignette', new Image\Base(dirname(__FILE__) . '/source/vignette.png'));

$image->imagePng();
