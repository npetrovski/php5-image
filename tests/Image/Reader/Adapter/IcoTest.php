<?php
namespace Image\Reader\Adapter;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-05-30 at 11:00:21.
 */
class IcoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Ico
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Ico;
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
     * @covers Image\Reader\Adapter\Ico::totalIcons
     */
    public function testTotalIcons()
    {
        $this->object->getImage(dirname(__FILE__) . '/../../../image.ico');
        
        $this->assertEquals(5, $this->object->totalIcons());
    }

    /**
     * @covers Image\Reader\Adapter\Ico::getIconInfo
     */
    public function testGetIconInfo()
    {
        $this->object->getImage(dirname(__FILE__) . '/../../../image.ico');
        
        $this->assertInternalType('array', $this->object->getIconInfo(0));
        
    }

    /**
     * @covers Image\Reader\Adapter\Ico::setBackground
     */
    public function testSetBackground()
    {
        $this->assertInstanceOf(get_class($this->object), $this->object->setBackground(255, 255, 255));
    }

    /**
     * @covers Image\Reader\Adapter\Ico::setBackgroundTransparent
     */
    public function testSetBackgroundTransparent()
    {
        $this->assertTrue($this->object->setBackgroundTransparent(true));
    }

    /**
     * @covers Image\Reader\Adapter\Ico::getImage
     */
    public function testGetImage()
    {
        $image = $this->object->getImage(dirname(__FILE__) . '/../../../image.ico');
        $this->assertTrue(($image && 'gd' == get_resource_type($image)));
    }
}
