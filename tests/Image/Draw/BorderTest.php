<?php
namespace Image\Draw;

use Image\Canvas as Canvas;

class BorderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Border
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Border;
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
     * @covers Image\Draw\Border::setBorder
     */
    public function testSetBorder()
    {
        $this->assertInstanceOf('Image\Draw\Border', $this->object->setBorder(10));
    }

    /**
     * @covers Image\Draw\Border::setPadding
     */
    public function testSetPadding()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setPadding(20));
    }

    /**
     * @covers Image\Draw\Border::setColor
     */
    public function testSetColor()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setColor('232323'));
    }

    /**
     * @covers Image\Draw\Border::generate
     */
    public function testGenerate()
    {
        $image = new Canvas(200, 200, '#ff0000');
        $image->attach($this->object);
        
        $this->assertTrue($this->object->generate());
    }
}
