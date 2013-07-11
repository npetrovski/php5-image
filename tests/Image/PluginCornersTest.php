<?php


class PluginCornersTest extends PHPUnit_Framework_TestCase {

    public function testPluginCorners() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new Image_Fx_Corners()), true);

    }

}
