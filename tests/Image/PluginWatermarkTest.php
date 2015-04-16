<?php

class PluginWatermarkTest extends PHPUnit_Framework_TestCase {

    public function testPluginWatermark() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Draw\Watermark()));

    }

}
