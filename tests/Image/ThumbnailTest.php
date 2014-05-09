<?php


    class ThumbnailTest extends PHPUnit_Framework_TestCase {

        public function testSetAndGet() {

            $image = new Image_Image();

            $image->value = 100;

            $this->assertEquals($image->value, 100);

        }

        public function testSetResizeShortcut() {

            $image = new Image_Image();
            $image->openImage(TEST_BASE.DIRECTORY_SEPARATOR."resize.png");

            $this->assertEquals($image->imagesx(), 320);
            $this->assertEquals($image->imagesy(), 240);
            
            $resize = new Image_Fx_Resize();
            $resize->setResize(100,100);

            $attachment_id = $image->attach($resize);

            $this->assertEquals($image->$attachment_id->resize_x, 100);
            $this->assertEquals($image->$attachment_id->resize_y, 100);
            
            $image->apply();

            $this->assertEquals($image->imagesx(), 100);
            $this->assertEquals($image->imagesy(), 100);

        }

        public function testSetCropShortcut() {

            $image = new Image_Image();
            $image->openImage(TEST_BASE.DIRECTORY_SEPARATOR."resize.png");

            $this->assertEquals($image->imagesx(), 320);
            $this->assertEquals($image->imagesy(), 240);
            
            $crop = new image_fx_crop();
            $crop->setCrop(100,100);

            $attachment_id = $image->attach($crop);

            $this->assertEquals($image->$attachment_id->crop_x, 100);
            $this->assertEquals($image->$attachment_id->crop_y, 100);
            
            $image->apply();

            $this->assertEquals($image->imagesx(), 100);
            $this->assertEquals($image->imagesy(), 100);

        }
        
        public function testCalculateResize() {

            $image = new Image_Image();
            $resize = new Image_Fx_Resize();
            $attachment_id = $image->attach($resize);

            if ($image->gd_support_png) {

                $this->assertEquals($image->openImage(TEST_BASE.DIRECTORY_SEPARATOR."resize.png"), true);
            
                $image->$attachment_id->setResize(160);
                $image->$attachment_id->calculate();
                $this->assertEquals($image->$attachment_id->canvas_x, 160);
                $this->assertEquals($image->$attachment_id->canvas_y, 120);

                $image->$attachment_id->setResize(0,60);
                $image->$attachment_id->calculate();
                $this->assertEquals($image->$attachment_id->canvas_x, 80);
                $this->assertEquals($image->$attachment_id->canvas_y, 60);

                $image->$attachment_id->setResize(100,50);
                $image->$attachment_id->calculate();
                $this->assertEquals($image->$attachment_id->canvas_x, 100);
                $this->assertEquals($image->$attachment_id->canvas_y, 50);

            }

        }
        
        public function testCalculateCropping() {

            $image = new Image_Image();
            $crop = new Image_Fx_Crop();
            $attachment_id = $image->attach($crop);
            
            if ($image->gd_support_png) {

                $this->assertEquals($image->openImage(TEST_BASE.DIRECTORY_SEPARATOR."resize.png"), true);
            
                $image->$attachment_id->setCrop(160);
                $image->$attachment_id->calculate();
                $this->assertEquals($image->$attachment_id->canvas_x, 160);
                $this->assertEquals($image->$attachment_id->canvas_y, 240);

                $image->$attachment_id->setCrop(0,60);
                $image->$attachment_id->calculate();
                $this->assertEquals($image->$attachment_id->canvas_x, 320);
                $this->assertEquals($image->$attachment_id->canvas_y, 60);

                $image->$attachment_id->setCrop(100,50);
                $image->$attachment_id->calculate();
                $this->assertEquals($image->$attachment_id->canvas_x, 100);
                $this->assertEquals($image->$attachment_id->canvas_y, 50);

            }

        }


        public function testCalculateResizeAndCrop() {

            $image = new Image_Image();

            $resize = new Image_Fx_Resize();
            $resize_id = $image->attach($resize);

            $crop = new Image_Fx_Crop();
            $crop_id = $image->attach($crop);

            if ($image->gd_support_png) {

                $this->assertEquals($image->openImage(TEST_BASE.DIRECTORY_SEPARATOR."resize.png"), true);
            
                $image->$resize_id->resize_x = 160;
                $image->$crop_id->crop_x = 120;
                
                $image->apply();
                
                $this->assertEquals($image->imagesx(), 120);
                $this->assertEquals($image->imagesy(), 120);

            }

        }

    }
