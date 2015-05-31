<?php

require_once dirname(__FILE__) . '/../autoload.php';

$image = new Image\Canvas(250, 100);
$image->draw('qrcode', 'https://github.com/npetrovski/php5-image');

$image->imagePng();
