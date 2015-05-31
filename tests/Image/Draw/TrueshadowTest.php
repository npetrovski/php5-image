<?php
namespace Image\Draw;

use Image\Canvas as Canvas;

class TrueshadowTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Trueshadow
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Trueshadow;
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
     * @covers Image\Draw\Trueshadow::setDistance
     */
    public function testSetDistance()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setDistance(10));
    }

    /**
     * @covers Image\Draw\Trueshadow::setColor
     */
    public function testSetColor()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setColor('555555'));
    }

    /**
     * @covers Image\Draw\Trueshadow::setMatrix
     */
    public function testSetMatrix()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setMatrix(array(1, 2, 4, 4, 8, 8, 12, 12, 16, 16, 24, 32, 32, 24, 16, 16, 12, 12, 8, 8, 4, 4, 2, 1)));
    }

    /**
     * @covers Image\Draw\Trueshadow::generate
     */
    public function testGenerate()
    {
        $image = new Canvas(200, 200, '#000000');
        $image->attach($this->object);
                        
        $this->assertTrue($this->object->generate());
    }
}
