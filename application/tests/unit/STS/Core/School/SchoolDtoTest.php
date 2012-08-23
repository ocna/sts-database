<?php
use STS\TestUtilities\SchoolTestCase;
use STS\Core\School\SchoolDto;

class SchoolDtoTest extends SchoolTestCase
{
    /**
     * @test
     */
    public function createValidSchoolDto()
    {
        $dto = new SchoolDto(self::ID, self::LEGACY_ID, self::NAME);
        $this->assertValidSchoolDto($dto);
    }
    private function assertValidSchoolDto($dto)
    {
        $this->assertInstanceOf('STS\Core\School\SchoolDto', $dto);
        $this->assertTrue(is_string($dto->getId()));
        $this->assertEquals(self::ID, $dto->getId());
        $this->assertEquals(self::LEGACY_ID, $dto->getLegacyId());
        $this->assertEquals(self::NAME, $dto->getName());
    }
}
