<?php
namespace Image\Fx;

use Image\Canvas as Canvas;

class RippleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Ripple
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Ripple;
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
     * @covers Image\Fx\Ripple::setRipple
     */
    public function testSetRipple()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setRipple(3, 4, true));
    }

    /**
     * @covers Image\Fx\Ripple::generate
     */
    public function testGenerate()
    {
        $image = new Canvas(200, 200, '#ff0000');
        $image->attach($this->object);
        
        $this->assertTrue($this->object->generate());
    }
}
