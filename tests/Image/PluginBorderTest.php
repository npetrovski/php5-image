<?php

class PluginBorderTest extends PHPUnit_Framework_TestCase {

    public function testPluginBorder() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new Image_Draw_Border()), true);

    }

}
