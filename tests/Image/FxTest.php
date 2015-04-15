<?php


class FXTest extends PHPUnit_Framework_TestCase {

    public function testFXStack() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Fx\Resize(50)));
        $this->assertNotEmpty($image->attach(new Image\Fx\Crop(50)));

    }

}
