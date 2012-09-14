<?php
use STS\TestUtilities\Location\AreaTestCase;

class AreaTest extends AreaTestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $this->assertValidArea($this->getValidArea());
    }
}
