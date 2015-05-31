<?php
namespace Image\Draw;

use Image\Canvas as Canvas;

class PrimitiveTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Primitive
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Primitive;
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
     * @covers Image\Draw\Primitive::line
     */
    public function testLine()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->line(0, 0, 10, 10));
    }

    /**
     * @covers Image\Draw\Primitive::rectangle
     */
    public function testRectangle()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->rectangle(0, 0, 10, 10));
    }

    /**
     * @covers Image\Draw\Primitive::filledRectangle
     */
    public function testFilledRectangle()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->filledRectangle(0, 0, 10, 10));
    }

    /**
     * @covers Image\Draw\Primitive::ellipse
     */
    public function testEllipse()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->ellipse(10, 10, 10, 20));
    }

    /**
     * @covers Image\Draw\Primitive::filledEllipse
     */
    public function testFilledEllipse()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->filledEllipse(10, 10, 10, 20));
    }

    /**
     * @covers Image\Draw\Primitive::circle
     */
    public function testCircle()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->circle(0, 0, 10));
    }

    /**
     * @covers Image\Draw\Primitive::spiral
     */
    public function testSpiral()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->spiral(0, 0, 10, 20));
    }

    /**
     * @covers Image\Draw\Primitive::generate
     */
    public function testGenerate()
    {
        $image = new Canvas(200, 200, '#000000');
        $image->attach($this->object);
                
        $this->assertTrue($this->object->generate());
    }
}
