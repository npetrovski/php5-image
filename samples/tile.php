<?php

require_once dirname(__FILE__) . '/../_autoload.php';

$image = new Image\Canvas(dirname(__FILE__) . '/source/stamens.jpg');

$image->attach(new Image\Fx\Resize(196));
$image->attach(new Image\Fx\Crop(0, 96));

$watermark = new Image\Draw\Watermark(new Image\Canvas(dirname(__FILE__) . '/source/watermark.png'));
$watermark->setPosition("tile");

$image->attach($watermark);

$image->attach(new Image\Draw\Border(2, "000000"));

$image->imagePng();
