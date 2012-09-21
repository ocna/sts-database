<?php
use STS\Core\Location\AreaDto;

class AreaDtoTest extends \PHPUnit_Framework_TestCase
{
    const ID = '502d8ff40172cda7d649d41b';
    const NAME = 'MI-Grand Rapids';
    const LEGACY_ID = 10;
    const STATE = 'MI';
    const CITY = 'Grand Rapids';
    const REGION_NAME = 'Mid-West';
    /**
     * @test
     */
    public function createValidAreaDto()
    {
        $dto = $this->getValidAreaDto();
        $this->assertValidAreaDto($dto);
    }
    private function getValidAreaDto()
    {
        $dto = new AreaDto(self::ID, self::NAME, self::LEGACY_ID, self::CITY, self::STATE, self::REGION_NAME);
        return $dto;
    }
    private function assertValidAreaDto($dto)
    {
        $this->assertEquals(self::ID, $dto->getId());
        $this->assertEquals(self::NAME, $dto->getName());
        $this->assertEquals(self::LEGACY_ID, $dto->getLegacyId());
        $this->assertEquals(self::STATE, $dto->getState());
        $this->assertEquals(self::CITY, $dto->getCity());
        $this->assertEquals(self::REGION_NAME, $dto->getRegionName());
    }
}
