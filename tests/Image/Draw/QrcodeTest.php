<?php

namespace Image\Draw;

use Image\Canvas as Canvas;

class QrcodeTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Qrcode
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Qrcode;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        unset($this->object);
    }

    /**
     * @covers Image\Draw\Qrcode::getLevel
     */
    public function testGetLevel() {
        $this->object->setLevel(10);

        $this->assertEquals(10, $this->object->getLevel());
    }

    /**
     * @covers Image\Draw\Qrcode::setLevel
     */
    public function testSetLevel() {
        $this->assertInstanceOf(get_class($this->object), $this->object->setLevel(10));
    }

    /**
     * @covers Image\Draw\Qrcode::getMargin
     */
    public function testGetMargin() {
        $this->object->setMargin(5);
        
        $this->assertEquals(5, $this->object->getMargin());
    }

    /**
     * @covers Image\Draw\Qrcode::setMargin
     */
    public function testSetMargin() {
        $this->assertInstanceOf(get_class($this->object), $this->object->setMargin(10));
    }

    /**
     * @covers Image\Draw\Qrcode::getText
     */
    public function testGetText() {
        $this->object->setText('test');
        
        $this->assertEquals('test', $this->object->getText());
    }

    /**
     * @covers Image\Draw\Qrcode::setText
     */
    public function testSetText() {
        $this->assertInstanceOf(get_class($this->object), $this->object->setText('test'));
    }

    /**
     * @covers Image\Draw\Qrcode::setColor
     */
    public function testSetColor() {
        $this->assertInstanceOf(get_class($this->object), $this->object->setColor('ff0000'));
    }

    /**
     * @covers Image\Draw\Qrcode::generate
     */
    public function testGenerate() {
        $image = new Canvas(200, 200, '#000000');
        $image->attach($this->object);
        
        $this->object->setText('test');
                
        $this->assertTrue($this->object->generate());
    }

    /**
     * @covers Image\Draw\Qrcode::getBarcodeArray
     */
    public function testGetBarcodeArray() {
        $this->object->setText('test');
        $this->assertInternalType('array', $this->object->getBarcodeArray());
    }

}
