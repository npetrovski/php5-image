<?php

class PluginWatermarkTest extends PHPUnit_Framework_TestCase {

    public function testPluginWatermark() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new Image_Draw_Watermark()), true);

    }

}
