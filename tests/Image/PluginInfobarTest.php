<?php

class PluginInfobarTest extends PHPUnit_Framework_TestCase {

    public function testPluginInfobar() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new Image_Draw_Infobar()), true);

    }

}
