<?php
namespace STS\Core\Presentation;

use STS\Core\Presentation\PresentationDto;
use STS\TestUtilities\PresentationTestCase;
use STS\TestUtilities\MemberTestCase;

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

    /**
     * @test
     */
    public function itsShouldReturnTheRightValuesForArray()
    {
        $member = MemberTestCase::createValidMember();
        $dto = $this->getValidPresentationDto();
        $members = $dto->getMembersArray();
        $this->assertEquals($member->getFullName(), $members[$member->getId()]['fullname']);
        $this->assertEquals($member->getStatus(), $members[$member->getId()]['status']);
    }
}
