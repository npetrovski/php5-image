<?php
namespace Image\Fx;

use Image\Canvas as Canvas;

class OffsetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Offset
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Offset;
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
     * @covers Image\Fx\Offset::setOffset
     */
    public function testSetOffset()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setOffset(5, 15));
    }

    /**
     * @covers Image\Fx\Offset::generate
     */
    public function testGenerate()
    {
        $image = new Canvas(200, 200, '#ff0000');
        $image->attach($this->object);
        
        $this->assertTrue($this->object->generate());
    }
}
