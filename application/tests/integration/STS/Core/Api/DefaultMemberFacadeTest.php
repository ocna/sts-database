<?php
use STS\Core;
use STS\TestUtilities\MemberTestCase;
use STS\Core\Api\DefaultMemberFacade;

class DefaultMemberFacadeTest extends MemberTestCase
{
    /**
     * @test
     */
    public function searchForMembersByName()
    {
        $facade = $this->loadFacadeInstance();
        $members = $facade->searchForMembersByNameWithSpec('member us', null);
        $this->assertTrue(is_array($members), 'The search did not return an array!');
        $this->assertValidMemberDto($members[0], 'The first returned member is different than expected!');
    }
    private function loadFacadeInstance()
    {
        $core = Core::getDefaultInstance();
        $facade = $core->load('MemberFacade');
        return $facade;
    }
}
