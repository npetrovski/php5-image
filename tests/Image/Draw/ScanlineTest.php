<?php
namespace Image\Draw;

use Image\Canvas as Canvas;

class ScanlineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Scanline
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Scanline;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        unset($this->object);
    }

    /**
     * @covers Image\Draw\Scanline::setWidth
     */
    public function testSetWidth()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setWidth(5));
    }

    /**
     * @covers Image\Draw\Scanline::setColor
     */
    public function testSetColor()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setColor('ffffff'));
    }

    /**
     * @covers Image\Draw\Scanline::setAlpha
     */
    public function testSetAlpha()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setAlpha(78));
    }

    /**
     * @covers Image\Draw\Scanline::generate
     */
    public function testGenerate()
    {
        $image = new Canvas(200, 200, '#000000');
        $image->attach($this->object);
                        
        $this->assertTrue($this->object->generate());
    }
}
