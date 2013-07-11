<?php


class PluginCropTest extends PHPUnit_Framework_TestCase {

    public function testPluginCrop() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new Image_Fx_Crop()), true);

    }

}
