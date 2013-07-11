<?php

class PluginOffsetTest extends PHPUnit_Framework_TestCase {

    public function testPluginOffset() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new Image_Fx_Offset()), true);

    }

}
