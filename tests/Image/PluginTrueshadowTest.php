<?php


class PluginTrueshadowTest extends PHPUnit_Framework_TestCase {

    public function testPluginTrueshadow() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new Image_Draw_Trueshadow()), true);

    }

}
