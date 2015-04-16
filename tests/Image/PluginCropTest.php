<?php


class PluginCropTest extends PHPUnit_Framework_TestCase {

    public function testPluginCrop() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Fx\Crop()));

    }

}
