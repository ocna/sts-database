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
        $members = $facade->searchForMembersByNameWithSpec('member te', null);
        $this->assertTrue(is_array($members));
        $this->assertValidMemberDto($members[0]);
        
    }
    private function loadFacadeInstance()
    {
        $core = Core::getDefaultInstance();
        $facade = $core->load('MemberFacade');
        return $facade;
    }
}
