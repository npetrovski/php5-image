<?php
namespace Image\Draw;

use Image\Canvas as Canvas;

class LayerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Layer
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Layer;
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
     * @covers Image\Draw\Layer::setLayer
     */
    public function testSetLayer()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setLayer(new Canvas));
    }

    /**
     * @covers Image\Draw\Layer::setPosition
     */
    public function testSetPosition()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setPosition(0, 0));
    }

    /**
     * @covers Image\Draw\Layer::generate.
     */
    public function testGenerate()
    {
        $image = new Canvas(200, 200, '#000000');
        $image->attach($this->object);
        
        $this->object->setLayer(new Canvas(200, 200));
        
        $this->assertTrue($this->object->generate());
    }
}
