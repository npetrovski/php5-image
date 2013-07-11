<?php

class PluginJitterTest extends PHPUnit_Framework_TestCase {

    public function testPluginJitter() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new image_fx_jitter()), true);

    }

}
