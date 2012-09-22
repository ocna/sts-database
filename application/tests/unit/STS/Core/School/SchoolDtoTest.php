<?php
use STS\TestUtilities\SchoolTestCase;

class SchoolDtoTest extends SchoolTestCase
{
    /**
     * @test
     */
    public function createValidSchoolDto()
    {
        $dto = $this->getValidSchoolDto();
        $this->assertValidSchoolDto($dto);
    }
}
