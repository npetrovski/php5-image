<?php

require_once dirname(__FILE__) . '/bootstrap.php';

$image = new Image\Base(250, 100);

//$image->attach(new Image\Draw\Qrcode("https://github.com/npetrovski/php5-image"));

$image->draw('qrcode', 'https://github.com/npetrovski/php5-image');

$image->imagePng();
