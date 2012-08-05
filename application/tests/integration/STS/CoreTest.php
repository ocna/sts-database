<?php
use STS\Core;

class CoreTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getValidDefaultInstance()
    {
        $core = Core::getDefaultInstance();
        $this->assertInstanceOf('STS\Core', $core);
    }
}
