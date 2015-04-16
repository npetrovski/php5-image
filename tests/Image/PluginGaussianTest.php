<?php

class PluginGaussianTest extends PHPUnit_Framework_TestCase {

    public function testPluginGaussian() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Fx\Gaussian()));

    }

}
