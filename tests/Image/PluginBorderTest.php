<?php

class PluginBorderTest extends PHPUnit_Framework_TestCase {

    public function testPluginBorder() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Draw\Border()));

    }

}
