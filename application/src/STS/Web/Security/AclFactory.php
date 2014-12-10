<?php
namespace STS\Web\Security;

use Zend_Acl;

class AclFactory
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
    const RESOURCE_REPORT = 'report';
    const RESOURCE_REGION = 'region';
	const RESOURCE_PROFESSIONAL_GROUP = 'professional_group';

    public static function buildAcl()
    {
        $acl = new Zend_Acl();

        // Add Roles
        $acl->addRole(self::ROLE_FACILITATOR);
        $acl->addRole(self::ROLE_COORDINATOR, self::ROLE_FACILITATOR);
        $acl->addRole(self::ROLE_ADMIN);

        // Add Resources
        $acl->addResource(self::RESOURCE_ADMIN);
        $acl->addResource(self::RESOURCE_PRESENTATION);
        $acl->addResource(self::RESOURCE_SEARCH);
        $acl->addResource(self::RESOURCE_MEMBER, self::RESOURCE_ADMIN);
        $acl->addResource(self::RESOURCE_USER, self::RESOURCE_ADMIN);
        $acl->addResource(self::RESOURCE_SCHOOL, self::RESOURCE_ADMIN);
        $acl->addResource(self::RESOURCE_PROFESSIONAL_GROUP, self::RESOURCE_ADMIN);
        $acl->addResource(self::RESOURCE_REPORT);
        $acl->addResource(self::RESOURCE_REGION, self::RESOURCE_ADMIN);

        // Establish Rules
        $acl->allow(self::ROLE_ADMIN);
        $acl->allow(self::ROLE_FACILITATOR, self::RESOURCE_PRESENTATION);
        $acl->allow(self::ROLE_FACILITATOR, self::RESOURCE_PRESENTATION, 'edit');
        $acl->allow(self::ROLE_FACILITATOR, self::RESOURCE_SEARCH);
        $acl->allow(self::ROLE_COORDINATOR, self::RESOURCE_ADMIN, 'view');
        $acl->allow(self::ROLE_COORDINATOR, self::RESOURCE_MEMBER, 'view');
        $acl->allow(self::ROLE_COORDINATOR, self::RESOURCE_REPORT, 'view');
        $acl->allow(self::ROLE_COORDINATOR, self::RESOURCE_SCHOOL, 'view');

        // explicit deny
        $acl->deny(self::ROLE_COORDINATOR, self::RESOURCE_MEMBER, 'edit');
        $acl->deny(self::ROLE_COORDINATOR, self::RESOURCE_MEMBER, 'delete');
        $acl->deny(self::ROLE_COORDINATOR, self::RESOURCE_SCHOOL, 'edit');
        $acl->deny(self::ROLE_COORDINATOR, self::RESOURCE_SCHOOL, 'delete');
        return $acl;
    }

    public static function getAvailableRoles()
    {
        $reflected = new \ReflectionClass(get_called_class());
        foreach ($reflected->getConstants() as $key => $value) {
            if (substr($key, 0, 5) == 'ROLE_') {
                $types[$key] = $value;
            }
        }
        return $types;
    }

    public static function getAvailableRole($key)
    {
        if (substr($key, 0, 5) != 'ROLE_') {
            throw new \InvalidArgumentException('Role key must begin with "ROLE_".');
        }
        if (!array_key_exists($key, static::getAvailableRoles())) {
            throw new \InvalidArgumentException('No such role with given key.');
        }
        $reflected = new \ReflectionClass(get_called_class());
        return $reflected->getConstant($key);
    }
}
