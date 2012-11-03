<?php

namespace STS\Core\Presentation;

use STS\TestUtilities\PresentationTestCase;

class PresentationDtoAssemblerTest extends PresentationTestCase
{
    /**
     * @test
     */
    public function itShouldReturnAValidDtoForDomainObject()
    {
        $presentation = $this->getValidObject();
        $dto = PresentationDtoAssembler::toDto($presentation);
        $this->assertValidPresentationDto($dto);
    }
}
