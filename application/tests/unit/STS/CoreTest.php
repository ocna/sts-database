<?php
use STS\Core;

class CoreTest extends PHPUnit_Framework_TestCase
{
    const INVALID_KEY = 'NoWay';
    /**
     * @test
     */
    public function getValidCoreInstance()
    {
        $core = $this->getValidCoreWithMockedDeps();
        $this->assertInstanceOf('STS\Core', $core);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Class does not exist (NoWay)
     */
    public function throwExceptionForInvalidKey()
    {
        $core = $this->getValidCoreWithMockedDeps();
        $core->load(self::INVALID_KEY);
    }
    private function getValidCoreWithMockedDeps()
    {
        $config = $this->getMockBuilder('Zend_Config')->disableOriginalConstructor()->getMock();
        $factory = $this->getMockBuilder('STS\Core\MongoFactory')->getMock();
        $cache = $this->getMockBuilder('STS\Core\Cache')->getMock();
        $core = new Core($config, $factory, $cache);
        return $core;
    }
}
