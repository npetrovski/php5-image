<?php
namespace Image\Fx;

use Image\Canvas as Canvas;

class CornersTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Corners
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Corners;
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
     * @covers Image\Fx\Corners::setRadius
     */
    public function testSetRadius()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setRadius(5));
    }

    /**
     * @covers Image\Fx\Corners::generate
     */
    public function testGenerate()
    {
        $image = new Canvas(200, 200, '#ff0000');
        $image->attach($this->object);
        
        $this->assertTrue($this->object->generate());
    }
}
