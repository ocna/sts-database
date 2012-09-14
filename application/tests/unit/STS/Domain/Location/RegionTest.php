<?php
use STS\TestUtilities\Location\RegionTestCase;

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
