<?php

class PluginScanlineTest extends PHPUnit_Framework_TestCase {

    public function testPluginScanline() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Draw\Scanline()));

    }

}
