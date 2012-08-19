<?php
use STS\TestUtilities\AreaTestCase;

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
