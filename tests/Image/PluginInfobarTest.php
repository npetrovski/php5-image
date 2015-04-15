<?php

class PluginInfobarTest extends PHPUnit_Framework_TestCase {

    public function testPluginInfobar() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Draw\Infobar()));

    }

}
