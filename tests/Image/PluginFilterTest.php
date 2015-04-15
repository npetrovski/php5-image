<?php


class PluginFilterTest extends PHPUnit_Framework_TestCase {

    public function testPluginFilter() {

        $image = new Image\Canvas();
        
        $this->assertNotEmpty($image->attach(new Image\Fx\Filter()));

    }

}
