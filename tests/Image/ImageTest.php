<?php

class ImageTest extends PHPUnit_Framework_TestCase {


    public function testGD2Extension() {

        $image = new Image_Image();
        //$this->assertWithinMargin($image->gd_version, 2, 1);

    }

    public function testCreateAndDestroyImage() {

        $image = new Image_Image();
        
        $image->createImage(100,100);
        $this->assertEquals($image->testImageHandle(), true);
        
        $image->destroyImage();
        $this->assertTrue(!isset($image->image));

    }

    public function testOpenAndDestroyImage_GIF() {

        $image = new Image_Image();
        
        if ($image->gd_support_gif) {
            $this->assertEquals($image->openImage(TEST_BASE.DIRECTORY_SEPARATOR."image.gif"), true);
            $this->assertEquals(get_resource_type($image->image), "gd");
            $image->destroyImage();
            $this->assertNull($image->image);
        }

    }

    public function testOpenAndDestroyImage_PNG() {

        $image = new Image_Image();
        
        if ($image->gd_support_png) {
            $this->assertEquals($image->openImage(TEST_BASE.DIRECTORY_SEPARATOR."image.png"), true);
            $this->assertEquals(get_resource_type($image->image), "gd");
            $image->destroyImage();
            $this->assertNull($image->image);
        }

    }

    public function testOpenAndDestroyImage_JPEG() {

        $image = new Image_Image();
        
        if ($image->gd_support_jpg) {
            $this->assertEquals($image->openImage(TEST_BASE.DIRECTORY_SEPARATOR."image.jpg"), true);
            $this->assertEquals(get_resource_type($image->image), "gd");
            $image->destroyImage();
            $this->assertNull($image->image);
        }

    }

    public function testImageIsTrueColor() {

        $image = new Image_Image();

        if ($image->gd_support_gif) {
            $this->assertEquals($image->openImage(TEST_BASE.DIRECTORY_SEPARATOR."image.gif"), true);
            $this->assertEquals($image->imageIsTrueColor(), false);
        }

        $image->destroyImage();
        $this->assertNull($image->image);

        if ($image->gd_support_jpg) {
            $this->assertEquals($image->openImage(TEST_BASE.DIRECTORY_SEPARATOR."image.jpg"), true);
            $this->assertEquals($image->imageIsTrueColor(), true);
        }

        $image->destroyImage();
        $this->assertNull($image->image);
        
    }

    public function testImageColorAt() {

        $image = new Image_Image();

        if ($image->gd_support_gif) {
            $this->assertEquals($image->openImage(TEST_BASE.DIRECTORY_SEPARATOR."image.gif"), true); //8 bit GIF image
            $this->assertEquals($image->imageColorAt(3,3), 255); //Solid blue
        }

        $image->destroyImage();
        $this->assertNull($image->image);

        if ($image->gd_support_png) {
            $this->assertEquals($image->openImage(TEST_BASE.DIRECTORY_SEPARATOR."image.png"), true); //32 bit PNG image
            $this->assertEquals($image->imageColorAt(3,3), 255); //Solid blue
        }

        $image->destroyImage();
        $this->assertNull($image->image);

        if ($image->gd_support_jpg) {
            $this->assertEquals($image->openImage(TEST_BASE.DIRECTORY_SEPARATOR."image.jpg"), true); //24 bit JPEG image
            $this->assertEquals($image->imageColorAt(3,3), 4537); //Solid blue (with jpg compression so it's the wrong color)
        }

        $image->destroyImage();
        $this->assertNull($image->image);

    }
    
    public function testEvaluateFXStack() {
    
        $image = new Image_Image();
        $analyser = $image->attach(new Image_Helper_Analyser());

        if ($image->gd_support_png) {
            $this->assertEquals($image->openImage(TEST_BASE.DIRECTORY_SEPARATOR."image.png"), true); //32 bit PNG image
            $this->assertEquals($image->evaluateFXStack(), true);
        }
   
    }

}
