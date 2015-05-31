<?php
namespace Image\Fx;

use Image\Canvas as Canvas;

class ColorizeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Colorize
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Colorize;
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
     * @covers Image\Fx\Colorize::setColorize
     */
    public function testSetColorize()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setColorize('0f0f0f', 'ffffff'));
    }

    /**
     * @covers Image\Fx\Colorize::generate
     */
    public function testGenerate()
    {
        $image = new Canvas(200, 200, '#ff0000');
        $image->attach($this->object);
        
        $this->assertTrue($this->object->generate());
    }
}
