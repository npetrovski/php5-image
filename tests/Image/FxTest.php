<?php


class FXTest extends PHPUnit_Framework_TestCase {

    public function testFXStack() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new Image_Fx_Resize(50)), true);
        $this->assertEquals($image->attach(new Image_Fx_Crop(50)), true);

    }

}
