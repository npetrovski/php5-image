<?php

class PluginScanlineTest extends PHPUnit_Framework_TestCase {

    public function testPluginScanline() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new Image_Draw_Scanline()), true);

    }

}
