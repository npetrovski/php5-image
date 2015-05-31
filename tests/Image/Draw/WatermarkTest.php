<?php

namespace Image\Draw;

use Image\Canvas as Canvas;

class WatermarkTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Watermark
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Watermark;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        unset($this->object);
    }

    /**
     * @covers Image\Draw\Watermark::setWatermark
     */
    public function testSetWatermark() {
        $this->assertInstanceOf(get_class($this->object), $this->object->setWatermark('image.png'));
    }

    /**
     * @covers Image\Draw\Watermark::setPosition
     */
    public function testSetPosition() {
        $this->assertInstanceOf(get_class($this->object), $this->object->setPosition(10, 10));
    }

    /**
     * @covers Image\Draw\Watermark::generate
     */
    public function testGenerate() {
        $image = new Canvas(200, 200, '#000000');
        $image->attach($this->object);

        $this->object->setWatermark(dirname(__FILE__) . '/../../image.png');

        $this->assertTrue($this->object->generate());
    }

}
