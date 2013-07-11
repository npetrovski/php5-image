<?php


class PluginFilterTest extends PHPUnit_Framework_TestCase {

    public function testPluginFilter() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new Image_Fx_Filter()), true);

    }

}
