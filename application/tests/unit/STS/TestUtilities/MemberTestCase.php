<?php
namespace STS\TestUtilities;
use STS\Domain\Member;
use STS\Core\Member\MemberDto;

class MemberTestCase extends \PHPUnit_Framework_TestCase
{
    const ID = '50234bc4fe65f50a9579a8cd';
    const LEGACY_ID = 0;
    const FIRST_NAME = 'Member';
    const LAST_NAME = 'TestMember';
    const TYPE = 'Survivor';
    const NOTES = 'This is an interesting note!';
    const DECEASED = true;
    
    protected function getValidMember()
    {
        $member = new Member();
        $member->setId(self::ID)->setLegacyId(self::LEGACY_ID)->setFirstName(self::FIRST_NAME)
            ->setLastName(self::LAST_NAME)->setNotes(self::NOTES)->hasPassedAway()->setType(self::TYPE);
        return $member;
    }
    protected function getValidMemberDto()
    {
        $memberDto = new MemberDto(self::ID, self::LEGACY_ID, self::FIRST_NAME, self::LAST_NAME);
        return $memberDto;
    }
    protected function assertValidMember($member)
    {
        $this->assertInstanceOf('STS\Domain\Member', $member);
        $this->assertEquals($this->getValidMember(), $member);
        $this->assertEquals(self::TYPE, $member->getType());
    }
    protected function assertValidMemberDto($memberDto)
    {
        $this->assertInstanceOf('STS\Core\Member\MemberDto', $memberDto);
        $this->assertEquals($this->getValidMemberDto(), $memberDto);
    }
}
