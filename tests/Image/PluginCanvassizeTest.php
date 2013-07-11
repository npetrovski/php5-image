<?php


class PluginCanvassizeTest extends PHPUnit_Framework_TestCase {

    public function testPluginCanvassize() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new Image_Fx_Canvassize()), true);

    }

}
