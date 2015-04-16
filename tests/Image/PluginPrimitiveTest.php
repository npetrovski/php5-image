<?php

class PluginPrimitiveTest extends PHPUnit_Framework_TestCase {

    public function testPluginPrimitive() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Draw\Primitive()));

    }

}
