<?php
namespace STS\TestUtilities;
use STS\Domain\Presentation;
use STS\TestUtilities\PresentationTestCase;
use STS\TestUtilities\Location\AreaTestCase;
use STS\Domain\Member;
use STS\Domain\User;

class PresentationTest extends PresentationTestCase
{
    /**
     * @test
     */
    public function getListOfAllowableTypes()
    {
        $this
            ->assertEquals(array(
                    'TYPE_MED' => 'MED', 'TYPE_PA' => 'PA', 'TYPE_NP' => 'NP', 'TYPE_NS' => 'NS',
                    'TYPE_RES_OBGYN' => 'RES OBGYN', 'TYPE_RES_INT' => 'RES INT', 'TYPE_OTHER' => 'OTHER'
            ), Presentation::getAvailableTypes());
    }
    /**
     * @test
     */
    public function createValidInstanceOfPresentation()
    {
        $presentation = $this->getValidObject();
        $this->assertValidObject($presentation);
    }

    /**
     * @test
     */
    public function shouldBeAccessableByAdminUser()
    {
        $member = \Mockery::mock('Member');
        $user = \Mockery::mock('User', array('getRole'=>'admin'));
        $presentation = $this->getValidObject();
        $this->assertTrue($presentation->isAccessableByMemberUser($member, $user));
    }

    /**
     * @test
     */
    public function shouldBeAccessableToEntryUser()
    {
        $member = \Mockery::mock('Member');
        $user = \Mockery::mock('User', array('getRole'=>'member', 'getId'=>'jfox'));
        $presentation = $this->getValidObject();
        $this->assertTrue($presentation->isAccessableByMemberUser($member, $user));
    }

    /**
     * @test
     */
    public function shouldBeAccessableIfInAssociatedArea()
    {
        $member = MemberTestCase::createValidMember();
        $area = AreaTestCase::createValidArea();
        $member->canCoordinateForArea($area);
        $user = \Mockery::mock('User', array('getRole'=>'coordinator', 'getId'=>'cuser'));
        $presentation = $this->getValidObject();
        $this->assertTrue($presentation->isAccessableByMemberUser($member, $user));
    }

    /**
     * @test
     */
    public function shouldNotBeAccessableIfNotInAssociatedArea()
    {
        $member = MemberTestCase::createValidMember();
        $user = \Mockery::mock('User', array('getRole'=>'coordinator', 'getId'=>'cuser'));
        $presentation = $this->getValidObject();
        $this->assertFalse($presentation->isAccessableByMemberUser($member, $user));
    }

}
