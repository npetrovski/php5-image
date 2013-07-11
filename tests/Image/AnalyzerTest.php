<?php

class AnalyzerTest extends PHPUnit_Framework_TestCase
{

    public function testSize() {

        $image = new Image_Image();
        $analyser = $image->attach(new Image_Helper_Analyser());
        $image->openImage(TEST_BASE. DIRECTORY_SEPARATOR.'image.png');
        
        $this->assertEquals($image->testImageHandle(), true);
        $this->assertEquals($image->imagesx(), 100);
        $this->assertEquals($image->imagesy(), 100);
        $image->destroyImage();

    }
/*
    public function testCountColors() {

        $image = new Image_Image();
        $analyser = $image->attach(new Image_Helper_Analyser());
        
        $image->openImage(TEST_BASE. DIRECTORY_SEPARATOR. 'image.png');

        $this->assertEquals($image->$analyser->countColors(), 19);
        $image->destroyImage();

    }

    public function testHue() {

        $image = new Image_Image();
        $analyser = $image->attach(new Image_Helper_Analyser());
        $image->openImage(TEST_BASE.DIRECTORY_SEPARATOR.'lightgreen.png');
        $this->assertEquals($image->$analyser->hue(3, 3), 120);
        $image->destroyImage();

    }

    public function testBrightness() {

        $image = new Image_Image();
        $analyser = $image->attach(new Image_Helper_Analyser());
        $image->openImage(TEST_BASE.DIRECTORY_SEPARATOR.'lightgreen.png');
        $this->assertEquals($image->$analyser->brightness(3, 3), 100);
        $image->destroyImage();

    }

    public function testSaturation() {

        $image = new Image_Image();
        $analyser = $image->attach(new Image_Helper_Analyser());
        $image->openImage(TEST_BASE.DIRECTORY_SEPARATOR.'lightgreen.png');
        $this->assertEquals($image->$analyser->saturation(3, 3), 50);
        $image->destroyImage();

    }

    public function testAverageChannel() {

        $image = new Image_Image();
        $analyser = $image->attach(new Image_Helper_Analyser());
        $image->openImage(TEST_BASE.DIRECTORY_SEPARATOR.'image.png');
        $this->assertEquals($image->$analyser->averageChannel('r'), 218);
        $this->assertEquals($image->$analyser->averageChannel('g'), 218);
        $this->assertEquals($image->$analyser->averageChannel('b'), 218);
        $image->destroyImage();

    }

    public function testMaxChannel() {

        $image = new Image_Image();
        $analyser = $image->attach(new Image_Helper_Analyser());
        $image->openImage(TEST_BASE.DIRECTORY_SEPARATOR.'image.png');
        $this->assertEquals($image->$analyser->maxChannel('r'), 255);
        $this->assertEquals($image->$analyser->maxChannel('g'), 255);
        $this->assertEquals($image->$analyser->maxChannel('b'), 255);
        $image->destroyImage();

    }

    public function testMinChannel() {

        $image = new Image_Image();
        $analyser = $image->attach(new Image_Helper_Analyser());
        $image->openImage(TEST_BASE.DIRECTORY_SEPARATOR.'image.png');
        $this->assertEquals($image->$analyser->minChannel('r'), 1);
        $this->assertEquals($image->$analyser->minChannel('g'), 1);
        $this->assertEquals($image->$analyser->minChannel('b'), 1);
        $image->destroyImage();

    }

    public function testImageHue() {

        $image = new Image_Image();
        $analyser = $image->attach(new Image_Helper_Analyser());
        $image->openImage(TEST_BASE.DIRECTORY_SEPARATOR.'green.png');
        $this->assertEquals($image->$analyser->imageHue(), 120);
        $image->destroyImage();

    }

    public function testImageSaturation() {

        $image = new Image_Image();
        $analyser = $image->attach(new Image_Helper_Analyser());
        $image->openImage(TEST_BASE.DIRECTORY_SEPARATOR.'green.png');
        $this->assertEquals($image->$analyser->imageSaturation(), 100);
        $image->destroyImage();

    }

    public function testImageBrightness() {

        $image = new Image_Image();
        $analyser = $image->attach(new Image_Helper_Analyser());
        $image->openImage(TEST_BASE.DIRECTORY_SEPARATOR.'green.png');
        $this->assertEquals($image->$analyser->imageBrightness(), 100);
        $image->destroyImage();

    }
*/
}
