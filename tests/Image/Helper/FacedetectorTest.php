<?php
namespace Image\Helper;

use Image\Canvas as Canvas;

class FacedetectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Facedetector
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Facedetector;
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
     * @covers Image\Helper\Facedetector::drawFaceRectangle
     */
    public function testDrawFaceRectangle()
    {
        $image = new Canvas('portrait.jpg');
        $image->attach($this->object);
        $image->apply();
        
        $this->assertInstanceOf(get_class($this->object), $this->object->drawFaceRectangle('ff00ff'));
    }

    /**
     * @covers Image\Helper\Facedetector::generate
     */
    public function testGenerate()
    {
        $image = new Canvas('portrait.jpg');
        $image->attach($this->object);
                
        $this->assertTrue($this->object->generate());
    }
}
