<?php

class PluginJitterTest extends PHPUnit_Framework_TestCase {

    public function testPluginJitter() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Fx\Jitter()));

    }

}
