<?php

class PluginVignetteTest extends PHPUnit_Framework_TestCase {

    public function testPluginVignette() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new Image_Fx_Vignette()), true);

    }

}
