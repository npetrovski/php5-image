<?php

class PluginGaussianTest extends PHPUnit_Framework_TestCase {

    public function testPluginGaussian() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new image_fx_gaussian()), true);

    }

}
