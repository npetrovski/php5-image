<?php

class PluginVignetteTest extends PHPUnit_Framework_TestCase {

    public function testPluginVignette() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Fx\Vignette(new Image\Canvas())));
    }

}
