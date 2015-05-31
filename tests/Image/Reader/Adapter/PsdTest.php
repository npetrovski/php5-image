<?php
namespace Image\Reader\Adapter;


class PsdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Psd
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Psd;
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
     * @covers Image\Reader\Adapter\Psd::getImage
     */
    public function testGetImage()
    {
        $image = $this->object->getImage('image.psd');
        $this->assertTrue(($image && 'gd' == get_resource_type($image)));
    }
}
