<?php
namespace STS\Core\Api;

use STS\TestUtilities\PresentationTestCase;
use STS\TestUtilities\UserTestCase;
use STS\TestUtilities\MemberTestCase;

class DefaultPresentationFacadeTest extends PresentationTestCase
{
    const ADMIN_USER_ID = 'muser';
    /**
     * @test
     */
    public function itShouldReturnAllPresentationsForAnAdminUser()
    {
        $facade = $this->getFacadeWithMockedDeps();
        $presentations = $facade->getPresentationsForUserId(self::ADMIN_USER_ID);
        $this->assertTrue(is_array($presentations));
        $this->assertCount(3, $presentations);
        $this->assertInstanceOf('STS\Core\Presentation\PresentationDto', $presentations[0]);
    }

    private function getFacadeWithMockedDeps()
    {
        $presentations = array($this->getValidObject(), $this->getValidObject(), $this->getValidObject());
        $presentationRepository = \Mockery::mock('STS\Core\Presentation\MongoPresentationRepository', array('find'=>$presentations));
        $userRepository = \Mockery::mock('STS\Core\User\MongoUserRepository', array('load'=>UserTestCase::createValidUser()));
        $memberRepository = \Mockery::mock('STS\Core\Member\MongoMemberRepository', array('load'=>MemberTestCase::createValidMember()));
        $facade = new DefaultPresentationFacade($presentationRepository, $userRepository, $memberRepository);
        return $facade;
    }
}
