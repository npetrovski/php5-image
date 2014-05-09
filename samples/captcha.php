<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Base();

$image->createImageTrueColor(206, 96, "FF0000");

//Primitives
$background = new Image\Draw\Primitive("FFFFFF", 20);
$background->addLine(20, 20, 80, 80);
$background->addRectangle(100, 20, 180, 80);
$background->addFilledRectangle(150, 10, 170, 30);

$background->addEllipse(10, 50, 20, 60);
$background->addFilledEllipse(140, 60, 160, 80);

$background->addCircle(200, 50, 30);

$background->addSpiral(100, 50, 100, 10);

$image->attach($background);

//Captcha text
$captcha = new Image\Draw\Captcha("captcha");

$captcha->addTTFFont(dirname(__FILE__) . '/../fonts/blambotcustom.ttf');
$captcha->addTTFFont(dirname(__FILE__) . '/../fonts/adventure.ttf');
$captcha->addTTFFont(dirname(__FILE__) . '/../fonts/bluehigh.ttf');

$captcha->setTextSize(20)
        ->setSizeRandom(20)
        ->setAngleRandom(60)
        ->setTextSpacing(5)
        ->setTextColor("ffff00");

$image->attach($captcha);

//Add a border
$image->attach(new Image\Draw\Border(1, "BBBBBB"));
$image->attach(new Image\Draw\Border(1, "FFFFFF"));

$image->imagePng();
