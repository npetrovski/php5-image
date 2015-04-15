<?php

class PluginOffsetTest extends PHPUnit_Framework_TestCase {

    public function testPluginOffset() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Fx\Offset()));

    }

}
