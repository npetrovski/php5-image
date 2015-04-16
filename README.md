# PHP5 Image 

[![Build status](https://travis-ci.org/npetrovski/php5-image.svg?branch=master)](https://travis-ci.org/npetrovski/php5-image)

PHP5 Image is a full **object-oriented library for an image manipulation** by PHP and GD2. No imagick extension is required.

The project currently provides readers for PNG, JPEG, GIF, PSD, ICO image-file types, and outputs all GD2-supported types.

## Requirements

- PHP >=5.3
- GD2 extension

## Examples:
![php5-image](/php5-image.png "Title")

### Canvas Size
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/beach.jpg');

$image->fx('resize', 200)
      ->fx('crop', 0, 86)
      ->draw('border', 2, '000000')
      ->fx('canvassize', 0, 10, 10, 0)
      ->draw('watermark', dirname(__FILE__) . '/source/button.png', 'br');

$image->imagePng();
```

### Captcha
```php
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
```

### Colorize
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/red.jpg');

$image->fx('resize', 196)
      ->fx('crop', 0, 100)
      ->fx('colorize', 'ff0000', '00ff00');

$image->imagePng();
```

### Face Detector
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/portrait.jpg');

$image->fx('resize', 198)
      ->fx('crop', 196, 96)
      ->helper('facedetector')
      ->apply()
      ->drawFaceRectangle();

$image->imagePng();
```

### Filters
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/face.jpg');

$image->fx('resize', 198)
      ->fx('crop', 124, 94)
      ->fx('filter', IMG_FILTER_NEGATE)
      ->draw('border', 1, 'FFFFFF')
      ->draw('border', 1, 'BBBBBB')
      ->draw('border', 1, 'FFFFFF');

$image->imagePng();
```

### Gaussian Blur
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/seats.jpg');

$image->fx('resize', 250)
      ->fx('crop', 206, 96)
      ->fx('gaussian')
      ->draw('border', 1, 'BBBBBB')
      ->draw('border', 1, 'FFFFFF');

$image->imagePng();
```

### ICO Parser
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/iPodnanoorange.ico');

$image->fx('resize', 196)
      ->fx('crop', 0, 100);

$image->imagePng();
```

### Information Bar
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/road.jpg');

$image->fx('resize', 250)
        ->fx('crop', 196, 70)
        ->draw('infobar', '[Filename]', 't', 'c', 'FFFFBB', '000000')
        ->fx('corners', 10, 10)
        ->draw('border', 5, '000000')
        ->fx('corners', 12, 12);

$image->imagePng();
```

### Jitter
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/planes.jpg');

$image->fx('resize', 250)
        ->fx('crop', 206, 96)
        ->fx('jitter')
        ->draw('border', 1, 'BBBBBB')
        ->draw('border', 1, 'FFFFFF');

$image->imagePng();
```

### Layers
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/rose.jpg');
$layer = new Image\Canvas(dirname(__FILE__) . '/source/cherry.png');

$image->fx('corners', 15, 15)
      ->fx('resize', 198)
      ->fx('crop', 196, 96)
      ->draw('layer', $layer, 0, 0, false);

$image->imagePng();
```

### PSD Parser
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/iPod.psd');

$image->fx('resize', 196)
      ->fx('crop', 0, 100);

$image->imagePng();
```

### QR Code
```php
$image = new Image\Canvas(250, 100);
$image->draw('qrcode', 'https://github.com/npetrovski/php5-image');

$image->imagePng();
```

### Border + Corners
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/icecream.jpg');

$image->fx('resize', 196)
      ->fx('crop', 166, 70)
      ->fx('corners', 15, 15)
      ->draw('border', 5, 'FF0000')
      ->fx('corners', 17, 17)
      ->draw('border', 5, 'FF8888')
      ->fx('corners', 20, 20)
      ->draw('border', 5, 'FFCCCC')
      ->fx('corners', 22, 22);

$image->imagePng();
```

### Ripple
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/field.jpg');

$image->fx('resize', 200)
      ->fx('crop', 0, 90)
      ->fx('ripple')
      ->fx('corners', 13, 13)
      ->draw('border', 5, 'FFFFFF')
      ->fx('corners', 15, 15);

$image->imagePng();
```

### Scanlines
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/quay.jpg');

$image->fx('resize', 250)
      ->fx('crop', 206, 100)
      ->draw('scanline', 2, 'FFFFFF', 120, 110)
      ->fx('corners', 15, 15);

$image->imagePng();
```

### Sobel Edge Detection
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/flowers.jpg');

$image->fx('resize', 196)
      ->fx('crop', 0, 100)
      ->fx('sobel');

$image->imagePng();
```

### Black and White
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/car.jpg');

$image->fx('resize', 198)
      ->fx('crop', 156, 60)
      ->fx('blackandwhite')
      ->draw('border', 2, '000000')
      ->draw('border', 1, 'ffffff')
      ->draw('border', 17, '000000');

$image->imagePng();
```

### Tiled Watermark
```php
$image = new Image_Image(dirname(__FILE__) . '/source/stamens.jpg');

$image->attach(new Image_Fx_Resize(196));
$image->attach(new Image_Fx_Crop(0,96));

$watermark = new Image_Draw_Watermark(new Image_Image(dirname(__FILE__) . '/source/watermark.png'));

$watermark->setPosition("tile");
$image->attach($watermark);
$image->attach(new Image_Draw_Border(2, "000000"));
$image->imagePng();
```

### True Shadow
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/reflect.jpg');

$image->fx('resize', 206)
      ->fx('crop', 194, 88)
      ->fx('corners', 15, 15)
      ->draw('trueshadow', 5, "444444", array(1, 1, 1, 2, 2, 4, 4, 8, 4, 4, 2, 2, 1, 1, 1));

$image->imagePng();
```

### Vanilla
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/hotel.jpg');

$image->fx('resize', 200)
      ->fx('crop', 0, 94)
      ->draw('border', 1, 'FFFFFF')
      ->draw('border', 1, 'BBBBBB')
      ->draw('border', 1, 'FFFFFF');

$image->imagePng();
```

### Trim
```php
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
```

### Vignette
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/balloons.jpg');

$image->fx('resize', 250)
      ->fx('crop', 206, 100)
      ->fx('vignette', new Image\Canvas(dirname(__FILE__) . '/source/vignette.png'));

$image->imagePng();
```

### Rotate & Flip
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/apple.png');

$image->fx('rotate', 35)
      ->fx('flip', true, true)
      ->fx('resize', 100);

$image->imagePng();
```

### Watermark
```php
$image = new Image\Canvas(dirname(__FILE__) . '/source/boat.jpg');
$image->fx('resize', 198)
      ->fx('crop', 196, 96);
      
$wm_image = new Image\Canvas(dirname(__FILE__) . '/source/phpimage.png');
$wm_image->mid_handle = false;

$watermark = new Image\Draw\Watermark($wm_image);
$watermark->setPosition(0, 60);

$image->attach($watermark);
$image->draw('border', 2, "000000");

$image->imagePng();
```

## Contributing

Contributions are welcome. Please note the following guidelines before submiting your pull request.

- Follow [PSR-2](http://www.php-fig.org/psr/psr-2/) coding standards.
- Write tests for new functions and added features

## License

This library is licensed under the [MIT License](http://opensource.org/licenses/MIT).
