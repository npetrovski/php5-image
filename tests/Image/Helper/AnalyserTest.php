<?php
namespace Image\Helper;

use Image\Canvas as Canvas;


class AnalyserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Analyser
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Analyser;
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
     * @covers Image\Helper\Analyser::countColors
     */
    public function testCountColors()
    {
        $image = new Canvas();
        $image->attach($this->object);
        $image->openImage('image.png');

        $this->assertEquals($this->object->countColors(), 19);
        $image->destroyImage();
    }

    /**
     * @covers Image\Helper\Analyser::averageChannel
     */
    public function testAverageChannel()
    {
        $image = new Canvas();
        $image->attach($this->object);
        $image->openImage('image.png');
        
        $this->assertEquals($this->object->averageChannel('r'), 218);
        $this->assertEquals($this->object->averageChannel('g'), 218);
        $this->assertEquals($this->object->averageChannel('b'), 218);
        $image->destroyImage();
    }

    /**
     * @covers Image\Helper\Analyser::minChannel
     */
    public function testMinChannel()
    {
        $image = new Canvas();
        $image->attach($this->object);
        $image->openImage('image.png');
        
        $this->assertEquals($this->object->minChannel('r'), 1);
        $this->assertEquals($this->object->minChannel('g'), 1);
        $this->assertEquals($this->object->minChannel('b'), 1);
        $image->destroyImage();
    }

    /**
     * @covers Image\Helper\Analyser::maxChannel
     */
    public function testMaxChannel()
    {
        $image = new Canvas();
        $image->attach($this->object);
        $image->openImage('image.png');
        
        $this->assertEquals($this->object->maxChannel('r'), 255);
        $this->assertEquals($this->object->maxChannel('g'), 255);
        $this->assertEquals($this->object->maxChannel('b'), 255);
        $image->destroyImage();
    }

    /**
     * @covers Image\Helper\Analyser::hue
     */
    public function testHue()
    {
        $image = new Canvas();
        $image->attach($this->object);
        $image->openImage('lightgreen.png');
        
        $this->assertEquals($this->object->hue(3, 3), 120);
        $image->destroyImage();
    }

    /**
     * @covers Image\Helper\Analyser::saturation
     */
    public function testSaturation()
    {
        $image = new Canvas();
        $image->attach($this->object);
        $image->openImage('lightgreen.png');
        
        $this->assertEquals($this->object->saturation(3, 3), 50);
        $image->destroyImage();
    }

    /**
     * @covers Image\Helper\Analyser::brightness
     */
    public function testBrightness()
    {
        $image = new Canvas();
        $image->attach($this->object);
        $image->openImage('lightgreen.png');
        
        $this->assertEquals($this->object->brightness(3, 3), 100);
        $image->destroyImage();
    }

    /**
     * @covers Image\Helper\Analyser::imageHue
     */
    public function testImageHue()
    {
        $image = new Canvas();
        $image->attach($this->object);
        $image->openImage('green.png');
        
        $this->assertEquals($this->object->imageHue(), 120);
        $image->destroyImage();
    }

    /**
     * @covers Image\Helper\Analyser::imageSaturation
     */
    public function testImageSaturation()
    {
        $image = new Canvas();
        $image->attach($this->object);
        $image->openImage('green.png');
        
        $this->assertEquals($this->object->imageSaturation(), 100);
        $image->destroyImage();
    }

    /**
     * @covers Image\Helper\Analyser::imageBrightness
     */
    public function testImageBrightness()
    {
        $image = new Canvas();
        $analyser = $image->attach($this->object);
        $image->openImage('green.png');
        
        $this->assertEquals($this->object->imageBrightness(), 100);
        $image->destroyImage();
    }
}
