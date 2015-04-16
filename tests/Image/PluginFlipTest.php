<?php


class PluginFlipTest extends PHPUnit_Framework_TestCase {

    public function testPluginFlip() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Fx\Flip()));

    }

}
