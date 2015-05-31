<?php
namespace Image\Draw;

use Image\Canvas as Canvas;

class CaptchaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Captcha
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Captcha;
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
     * @covers Image\Draw\Captcha::addTTFFont
     */
    public function testAddTTFFont()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->addTTFFont('../fonts/acmesa.ttf'));
    }

    /**
     * @covers Image\Draw\Captcha::setTextSize
     */
    public function testSetTextSize()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setTextSize(12));
    }

    /**
     * @covers Image\Draw\Captcha::setTextSpacing
     */
    public function testSetTextSpacing()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setTextSpacing(5));
    }

    /**
     * @covers Image\Draw\Captcha::setSizeRandom
     */
    public function testSetSizeRandom()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setSizeRandom(2));
    }

    /**
     * @covers Image\Draw\Captcha::setAngleRandom
     */
    public function testSetAngleRandom()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setAngleRandom(45));
    }

    /**
     * @covers Image\Draw\Captcha::setTextColor
     */
    public function testSetTextColor()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setTextColor('000000'));
    }

    /**
     * @covers Image\Draw\Captcha::generate
     */
    public function testGenerate()
    {
        $image = new Canvas(200, 200, '#ff0000');
        $image->attach($this->object);
        
        $this->assertTrue($this->object->generate());
    }
}
