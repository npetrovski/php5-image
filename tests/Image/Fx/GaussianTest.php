<?php
namespace Image\Fx;

use Image\Canvas as Canvas;

class GaussianTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Gaussian
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Gaussian;
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
     * @covers Image\Fx\Gaussian::setMatrix
     */
    public function testSetMatrix()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setMatrix(array(1, 4, 8, 16, 8, 4, 1)));
    }

    /**
     * @covers Image\Fx\Gaussian::generate
     */
    public function testGenerate()
    {
        $image = new Canvas(200, 200, '#ff0000');
        $image->attach($this->object);
        
        $this->assertTrue($this->object->generate());
    }
}
