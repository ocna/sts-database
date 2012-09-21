<?php
use STS\Core\Location\RegionDto;
class RegionDtoTest extends \PHPUnit_Framework_TestCase{
    
    const NAME = 'Mid-West';
    const LEGACY_ID = 10;
    
    /**
     * @test
     */
    public function createValidRegionDto()
    {
        $dto = $this->getValidRegionDto();
        $this->assertValidRegionDto($dto);
    }
    
    private function getValidRegionDto(){
        $dto = new RegionDto(self::LEGACY_ID, self::NAME);
        return $dto;
    }
    
    private function assertValidRegionDto($dto){
        
        $this->assertEquals(self::LEGACY_ID, $dto->getLegacyId());
        $this->assertEquals(self::NAME, $dto->getName());
    
    }
}
