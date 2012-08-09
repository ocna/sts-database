<?php
use STS\TestUtilities\MemberTestCase;
use STS\Domain\Member;

class MemberTest extends MemberTestCase
{
    /**
     * @test
     */
    public function createValidObject()
    {
        $member = $this->getValidMember();
        $this->assertValidMember($member);
    }
}
