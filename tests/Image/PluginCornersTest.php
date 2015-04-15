<?php


class PluginCornersTest extends PHPUnit_Framework_TestCase {

    public function testPluginCorners() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Fx\Corners()));

    }

}
