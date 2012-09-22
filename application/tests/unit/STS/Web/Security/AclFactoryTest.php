<?php
use STS\Web\Security\AclFactory;

class AclFactoryTest extends \PHPUnit_Framework_TestCase
{
    const ROLE_ADMIN = 'admin';
    const ROLE_COORDINATOR = 'coordinator';
    const ROLE_FACILITATOR = 'facilitator';
    const RESOURCE_ADMIN = 'admin';
    const RESOURCE_PRESENTATION = 'presentation';
    const RESOURCE_MEMBER = 'member';
    const RESOURCE_USER = 'user';
    const RESOURCE_SCHOOL = 'school';
    const RESOURCE_SEARCH = 'search';
    /**
     * @test
     */
    public function validInitializeAclObject()
    {
        $acl = AclFactory::buildAcl();
        $this->assertTrue($acl instanceof \Zend_Acl);
    }
    /**
     * @test
     */
    public function confirmCorrectPermissionsForAdmin()
    {
        $acl = AclFactory::buildAcl();
        $this->assertTrue($acl->isAllowed(self::ROLE_ADMIN, self::RESOURCE_PRESENTATION));
        $this->assertTrue($acl->isAllowed(self::ROLE_ADMIN, self::RESOURCE_MEMBER));
        $this->assertTrue($acl->isAllowed(self::ROLE_ADMIN, self::RESOURCE_USER));
        $this->assertTrue($acl->isAllowed(self::ROLE_ADMIN, self::RESOURCE_SCHOOL));
        $this->assertTrue($acl->isAllowed(self::ROLE_ADMIN, self::RESOURCE_ADMIN));
        $this->assertTrue($acl->isAllowed(self::ROLE_ADMIN, self::RESOURCE_SEARCH));
    }
    /**
     * @test
     */
    public function confirmCorrectPermissionsForCoordinator()
    {
        $acl = AclFactory::buildAcl();
        $this->assertTrue($acl->isAllowed(self::ROLE_COORDINATOR, self::RESOURCE_PRESENTATION));
        $this->assertTrue($acl->isAllowed(self::ROLE_COORDINATOR, self::RESOURCE_SEARCH));
        $this->assertFalse($acl->isAllowed(self::ROLE_COORDINATOR, self::RESOURCE_MEMBER));
        $this->assertFalse($acl->isAllowed(self::ROLE_COORDINATOR, self::RESOURCE_USER));
        $this->assertFalse($acl->isAllowed(self::ROLE_COORDINATOR, self::RESOURCE_SCHOOL));
        $this->assertFalse($acl->isAllowed(self::ROLE_COORDINATOR, self::RESOURCE_ADMIN));
    }
    /**
     * @test
     */
    public function confirmCorrectPermissionsForFacilitator()
    {
        $acl = AclFactory::buildAcl();
        $this->assertTrue($acl->isAllowed(self::ROLE_FACILITATOR, self::RESOURCE_PRESENTATION));
        $this->assertTrue($acl->isAllowed(self::ROLE_FACILITATOR, self::RESOURCE_SEARCH));
        $this->assertFalse($acl->isAllowed(self::ROLE_FACILITATOR, self::RESOURCE_MEMBER));
        $this->assertFalse($acl->isAllowed(self::ROLE_FACILITATOR, self::RESOURCE_USER));
        $this->assertFalse($acl->isAllowed(self::ROLE_FACILITATOR, self::RESOURCE_SCHOOL));
        $this->assertFalse($acl->isAllowed(self::ROLE_FACILITATOR, self::RESOURCE_ADMIN));
    }
}
