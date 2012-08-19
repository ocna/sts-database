<?php
use STS\TestUtilities\RegionTestCase;

class RegionTest extends RegionTestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $this->assertValidRegion($this->getValidRegion());
    }
}
