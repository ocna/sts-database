<?php

namespace STS\Core\Member;

use STS\Domain\Member;
use STS\TestUtilities\MemberTestCase;
use STS\Core\Member\MemberDtoAssembler;
use STS\Domain\Location\Address;
use STS\TestUtilities\Location\AddressTestCase;

class MemberDtoAssemblerTest extends MemberTestCase
{
    /**
     * @test
     */
    public function getValidSchoolDTOFromSchoolDomainObject()
    {
        $member = $this->getValidMember();
        $dto = MemberDtoAssembler::toDTO($member);
        $this->assertValidMemberDto($dto);
    }
    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Instance of \STS\Domain\Member not
     * provided.
     */
    public function throwExceptionIfSchoolDomainObjectIsNotPassed()
    {
        $memberDto = MemberDtoAssembler::toDTO(null);
    }
}
