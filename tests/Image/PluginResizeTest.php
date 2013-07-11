<?php

class PluginResizeTest extends PHPUnit_Framework_TestCase {

    public function testPluginResize() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new Image_Fx_Resize()), true);

    }

}
