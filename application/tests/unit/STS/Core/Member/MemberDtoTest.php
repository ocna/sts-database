<?php
use STS\TestUtilities\MemberTestCase;
use STS\Core\Member\MemberDto;

class MemberDtoTest extends MemberTestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $memberDto = $this->getValidMemberDto();
        $this->assertValidMemberDto($memberDto);
    }
}
