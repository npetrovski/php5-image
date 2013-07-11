<?php

class PluginSobelTest extends PHPUnit_Framework_TestCase {

    public function testPluginSobel() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new Image_Fx_Sobel()), true);

    }

}
