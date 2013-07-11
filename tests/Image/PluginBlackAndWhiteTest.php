<?php


class PluginBlackAndWhiteTest extends PHPUnit_Framework_TestCase {

    public function testPluginBlackAndWhite() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new Image_Fx_Blackandwhite()), true);

    }

}
