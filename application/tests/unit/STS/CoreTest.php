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
     */
    public function getCorrectCoreObjectsForParameters()
    {
        $core = $this->getValidCoreWithMockedDeps();
        $loadableObjects = array(
            'AuthFacade'
        );
        foreach ($loadableObjects as $key) {
            $instance = $core->load($key);
            $this->assertInstanceOf('STS\Core\Api\\' . $key, $instance);
        }
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
        $core = new Core($config);
        return $core;
    }
}
