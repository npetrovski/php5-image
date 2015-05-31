<?php
namespace Image\Fx;

use Image\Canvas as Canvas;

class JitterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Jitter
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Jitter;
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
     * @covers Image\Fx\Jitter::setJitter
     */
    public function testSetJitter()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setJitter(3, true));
    }

    /**
     * @covers Image\Fx\Jitter::generate
     */
    public function testGenerate()
    {
        $image = new Canvas(200, 200, '#ff0000');
        $image->attach($this->object);
        
        $this->assertTrue($this->object->generate());
    }
}
