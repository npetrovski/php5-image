<?php


class PluginTrueshadowTest extends PHPUnit_Framework_TestCase {

    public function testPluginTrueshadow() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Draw\Trueshadow()));

    }

}
