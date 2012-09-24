<?php
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
        $address = new Address();
        $address->setLineOne(AddressTestCase::LINE_ONE)->setLineTwo(AddressTestCase::LINE_TWO)
            ->setZip(AddressTestCase::ZIP)->setState(AddressTestCase::STATE)->setCity(AddressTestCase::CITY);
        $member = new Member();
        $member->setId(MemberTestCase::ID)->setLegacyId(MemberTestCase::LEGACY_ID)
            ->setFirstName(MemberTestCase::FIRST_NAME)->setLastName(MemberTestCase::LAST_NAME)
            ->setNotes(MemberTestCase::NOTES)->hasPassedAway()->setType(MemberTestCase::TYPE)->setAddress($address);
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
