<?php
use STS\Core\Presentation\PresentationDto;
use STS\TestUtilities\PresentationTestCase;

class PresentationDtoTest extends PresentationTestCase
{
    /**
     * @test
     */
    public function createValidPresentationDto()
    {
        $dto = $this->getValidPresentationDto();
        $this->assertValidPresentationDto($dto);
    }
}
