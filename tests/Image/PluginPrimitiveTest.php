<?php

class PluginPrimitiveTest extends PHPUnit_Framework_TestCase {

    public function testPluginPrimitive() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new image_draw_primitive()), true);

    }

}
