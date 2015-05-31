<?php
namespace Image\Draw;

use Image\Canvas as Canvas;

class InfobarTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Infobar
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Infobar;
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
     * @covers Image\Draw\Infobar::setInfo
     */
    public function testSetInfo()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setInfo('image.png'));
    }

    /**
     * @covers Image\Draw\Infobar::setTextcolor
     */
    public function testSetTextcolor()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setTextcolor('333333'));
    }

    /**
     * @covers Image\Draw\Infobar::setBarcolor
     */
    public function testSetBarcolor()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setBarcolor('201232'));
    }

    /**
     * @covers Image\Draw\Infobar::setJustify
     */
    public function testSetJustify()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setJustify('c'));
    }

    /**
     * @covers Image\Draw\Infobar::setPosition
     */
    public function testSetPosition()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setPosition('b'));
    }

    /**
     * @covers Image\Draw\Infobar::generate
     */
    public function testGenerate()
    {
        $image = new Canvas(200, 200, '#000000');
        $image->attach($this->object);
        
        $this->assertTrue($this->object->generate());
    }
}
