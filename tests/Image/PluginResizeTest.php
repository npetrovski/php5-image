<?php

class PluginResizeTest extends PHPUnit_Framework_TestCase {

    public function testPluginResize() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Fx\Resize()));

    }

}
