<?php


class PluginFlipTest extends PHPUnit_Framework_TestCase {

    public function testPluginFlip() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new Image_Fx_Flip()), true);

    }

}
