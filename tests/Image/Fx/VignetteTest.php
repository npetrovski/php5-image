<?php
namespace Image\Fx;

use Image\Canvas as Canvas;

class VignetteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Vignette
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Vignette;
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
     * @covers Image\Fx\Vignette::setVignette
     */
    public function testSetVignette()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setVignette(new Canvas(200, 200)));
    }

    /**
     * @covers Image\Fx\Vignette::generate
     */
    public function testGenerate()
    {
        $image = new Canvas(200, 200, '#ff0000');
        $image->attach($this->object);
        
        $this->object->setVignette(new Canvas(200, 200));
        
        $this->assertTrue($this->object->generate());
    }
}
