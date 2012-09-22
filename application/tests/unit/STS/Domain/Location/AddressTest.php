<?php
use STS\TestUtilities\Location\AddressTestCase;

class AddressTest extends AddressTestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $this->assertValidAddress($this->getValidAddress());
    }
}
