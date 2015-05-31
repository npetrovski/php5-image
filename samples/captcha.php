<?php

require_once dirname(__FILE__) . '/../autoload.php';

$image = new Image\Canvas();
$image->createImageTrueColor(206, 96, "FF0000");

// Primitives
$background = new Image\Draw\Primitive("FFFFFF", 20);
$background->line(20, 20, 80, 80)
           ->rectangle(100, 20, 180, 80)
           ->filledRectangle(150, 10, 170, 30)
           ->ellipse(10, 50, 20, 60)
           ->filledEllipse(140, 60, 160, 80)
           ->circle(200, 50, 30)
           ->spiral(100, 50, 100, 10);

$image->attach($background);

// Captcha text
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

// Add border
$image->draw('border', 1, 'bbbbbb')
      ->draw('border', 1, 'ffffff');

$image->imagePng();
