# PHP5 Image Manipulation Library 

PHP5 Image is a full object-oriented library for an image manipulation by PHP and GD2. It is an extended version of [http://code.google.com/p/php-image/ php-image] project and can be used either standalone or inside [http://framework.zend.com/ Zend Framework] projects.

The project currently provides readers for PNG, JPEG, GIF, PSD, ICO image-file types, and outputs all GD2-supported types.

![php5-image](/php5-image.png "Title")

## Examples:

### Canvas Size
```php
$image = new Image_Image(dirname(__FILE__) . '/source/beach.jpg');
	
$image->attach(new Image_Fx_Resize(200));
$image->attach(new Image_Fx_Crop(0, 86));
$image->attach(new Image_Draw_Border(2, "000000"));
$image->attach(new Image_Fx_Canvassize(0, 10, 10, 0));
$watermark = new Image_Draw_Watermark(new Image_Image(dirname(__FILE__) . '/source/button.png'));
$watermark->setPosition("br");
$image->attach($watermark);
$image->imagePng();
```

### Captcha
```php
$image = new Image_Image();
$image->createImageTrueColor(206, 96, "FF0000");

//Primitives
$background = new Image_Draw_Primitive("FFFFFF", 20);
$background->addLine(20, 20, 80, 80);
$background->addRectangle(100, 20, 180, 80);
$background->addFilledRectangle(150, 10, 170, 30);
$background->addEllipse(10, 50, 20, 60);
$background->addFilledEllipse(140, 60, 160, 80);
$background->addCircle(200, 50, 30);
$background->addSpiral(100, 50, 100, 10);
$image->attach($background);

//Captcha text
$captcha = new Image_Draw_Captcha("captcha");
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
$image->attach(new Image_Draw_Border(1, "BBBBBB"));
$image->attach(new Image_Draw_Border(1, "FFFFFF"));
$image->imagePng();
```

### Colorize
```php
$image = new Image_Image(dirname(__FILE__) . '/source/red.jpg');

$image->attach(new Image_Fx_Resize(196));
$image->attach(new Image_Fx_Crop(0,100));
$image->attach(new Image_Fx_Colorize('ff0000', '00ff00'));
$image->imagePng();
```

### Face Detector
```php
$image = new Image_Image(dirname(__FILE__) . '/source/portrait.jpg');

$image->attach(new Image_Fx_Resize(198));
$image->attach(new Image_Fx_Crop(196,96));

$image->attach(new Image_Helper_FaceDetector());
$image->evaluateFXStack()->drawFaceRectangle();
$image->imagePng();
```

### Filters
```php
$image = new Image_Image(dirname(__FILE__) . '/source/face.jpg');

$image->attach(new Image_Fx_Resize(198));
$image->attach(new Image_Fx_Crop(194,94));
$image->attach(new Image_Fx_Filter(IMG_FILTER_NEGATE));
$image->attach(new Image_Draw_Border(1, "FFFFFF"));
$image->attach(new Image_Draw_Border(1, "BBBBBB"));
$image->attach(new Image_Draw_Border(1, "FFFFFF"));
$image->imagePng();
```

### Gaussian Blur
```php
$image = new Image_Image(dirname(__FILE__) . '/source/seats.jpg');

$image->attach(new Image_Fx_Resize(250));
$image->attach(new Image_Fx_Crop(206, 96));
$image->attach(new Image_Fx_Gaussian());
$image->attach(new Image_Draw_Border(1, "BBBBBB"));
$image->attach(new Image_Draw_Border(1, "FFFFFF"));
$image->imagePng();
```

### ICO Parser
```php
$image = new Image_Image(dirname(__FILE__) . '/source/iPodnanoorange.ico');

$image->attach(new Image_Fx_Resize(196));
$image->attach(new Image_Fx_Crop(0,100));
$image->imagePng();
```

### Information Bar
```php
$image = new Image_Image(dirname(__FILE__) . '/source/road.jpg');

$image->attach(new Image_Fx_Resize(250));
$image->attach(new Image_Fx_Crop(196, 70));
$image->attach(new Image_Draw_Infobar("[Filename]", "t", "c", "FFFFBB", "000000"));
$image->attach(new Image_Fx_Corners(10,10));
$image->attach(new Image_Draw_Border(5, "000000"));
$image->attach(new Image_Fx_Corners(12,12));
$image->imagePng();
```

### Jitter
```php
$image = new Image_Image(dirname(__FILE__) . '/source/planes.jpg');

$image->attach(new Image_Fx_Resize(250));
$image->attach(new Image_Fx_Crop(206, 96));
$image->attach(new Image_Fx_Jitter());
$image->attach(new Image_Draw_Border(1, "BBBBBB"));
$image->attach(new Image_Draw_Border(1, "FFFFFF"));
$image->imagePng();
```

### Layers
```php
$image = new Image_Image(dirname(__FILE__) . '/source/rose.jpg');

$image->attach(new Image_Fx_Resize(198));
$image->attach(new Image_Fx_Crop(196,96));

$secondImage = new Image_Image(dirname(__FILE__) . '/source/cherry.png');

$layer = new Image_Draw_Layer($secondImage);
$image->attach(new Image_Fx_Corners(15,15));
$image->attach($layer);
$image->imagePng();
```

### PSD Parser
```php
$image = new Image_Image(dirname(__FILE__) . '/source/iPod.psd');

$image->attach(new Image_Fx_Resize(196));
$image->attach(new Image_Fx_Crop(0,100));
$image->imagePng();
```

### QR Code
```php
$image = new Image_Image(250, 100);
$image->attach(new Image_Draw_QRCode("http://code.google.com/p/php5-image/"));

$image->imagePng();
```

### Border + Corners
```php
$image = new Image_Image(dirname(__FILE__) . '/source/icecream.jpg');

$image->attach(new Image_Fx_Resize(196));
$image->attach(new Image_Fx_Crop(166,70));
$image->attach(new Image_Fx_Corners(15,15));
$image->attach(new Image_Draw_Border(5, "FF0000"));
$image->attach(new Image_Fx_Corners(17,17));
$image->attach(new Image_Draw_Border(5, "FF8888"));
$image->attach(new Image_Fx_Corners(20,20));
$image->attach(new Image_Draw_Border(5, "FFCCCC"));
$image->attach(new Image_Fx_Corners(22,22));
$image->imagePng();
```

### Ripple
```php
$image = new Image_Image(dirname(__FILE__) . '/source/field.jpg');

$image->attach(new Image_Fx_Resize(200));
$image->attach(new Image_Fx_Crop(0, 90));
$image->attach(new Image_Fx_Ripple());
$image->attach(new Image_Fx_Corners(13,13));
$image->attach(new Image_Draw_Border(5, "FFFFFF"));
$image->attach(new Image_Fx_Corners(15,15));
$image->imagePng();
```

### Scanlines
```php
$image = new Image_Image(dirname(__FILE__) . '/source/quay.jpg');

$image->attach(new Image_Fx_Resize(250));
$image->attach(new Image_Fx_Crop(206, 100));
$image->attach(new Image_Draw_Scanline(2, "FFFFFF", 120, 110));
$image->attach(new Image_Fx_Corners(15,15));
$image->imagePng();
```

### Sobel Edge Detection
```php
$image = new Image_Image(dirname(__FILE__) . '/source/flowers.jpg');

$image->attach(new Image_Fx_Resize(196));
$image->attach(new Image_Fx_Crop(0,100));
$image->attach(new Image_Fx_Sobel());
$image->imagePng();
```

### Black and White
```php
$image = new Image_Image(dirname(__FILE__) . '/source/car.jpg');

$image->attach(new Image_Fx_Resize(198));
$image->attach(new Image_Fx_Crop(156,60));
$image->attach(new Image_Fx_Blackandwhite());
$image->attach(new Image_Draw_Border(2, "000000"));
$image->attach(new Image_Draw_Border(1, "FFFFFF"));
$image->attach(new Image_Draw_Border(17, "000000"));

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
$image = new Image_Image(dirname(__FILE__) . '/source/reflect.jpg');

$image->attach(new Image_Fx_Resize(206));
$image->attach(new Image_Fx_Crop(194, 88));
$image->attach(new Image_Fx_Corners(15,15));
$image->attach(new Image_Draw_Trueshadow(5, "444444", array(1,1,1,2,2,4,4,8,4,4,2,2,1,1,1)));
$image->imagePng();
```

### Vanilla
```php
$image = new Image_Image(dirname(__FILE__) . '/source/hotel.jpg');

$image->attach(new Image_Fx_Resize(200));
$image->attach(new Image_Fx_Crop(0, 94));
$image->attach(new Image_Draw_Border(1, "FFFFFF"));
$image->attach(new Image_Draw_Border(1, "BBBBBB"));
$image->attach(new Image_Draw_Border(1, "FFFFFF"));
$image->imagePng();
```

### Vignette
```php
$image = new Image_Image(dirname(__FILE__) . '/source/balloons.jpg');

$image->attach(new Image_Fx_Resize(250));
$image->attach(new Image_Fx_Crop(206, 100));

$image->attach(new Image_Fx_Vignette(new Image_Image(dirname(__FILE__) . '/source/vignette.png')));

$image->imagePng();
```

### Watermark
```php
$image = new Image_Image(dirname(__FILE__) . '/source/boat.jpg');

$image->attach(new Image_Fx_Resize(198));
$image->attach(new Image_Fx_Crop(196,96));

$wm_image = new Image_Image(dirname(__FILE__) . '/source/phpimage.png');

$wm_image->mid_handle = false;
$watermark = new Image_Draw_Watermark($wm_image);
$watermark->setPosition(0,60);
$image->attach($watermark);
$image->attach(new Image_Draw_Border(2, "000000"));
$image->imagePng();
```
