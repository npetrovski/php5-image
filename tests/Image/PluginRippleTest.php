<?php

class PluginRippleTest extends PHPUnit_Framework_TestCase {

    public function testPluginRipple() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Fx\Ripple()));

    }

}
