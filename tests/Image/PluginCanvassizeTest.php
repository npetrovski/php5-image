<?php


class PluginCanvassizeTest extends PHPUnit_Framework_TestCase {

    public function testPluginCanvassize() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Fx\Canvassize()));

    }

}
