<?php


class PluginBlackAndWhiteTest extends PHPUnit_Framework_TestCase {

    public function testPluginBlackAndWhite() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Fx\Blackandwhite()));

    }

}
