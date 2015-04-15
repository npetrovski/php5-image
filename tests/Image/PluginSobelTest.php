<?php

class PluginSobelTest extends PHPUnit_Framework_TestCase {

    public function testPluginSobel() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Fx\Sobel()));

    }

}
