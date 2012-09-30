<?php
namespace STS\Web\Security;

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
    public static function buildAcl()
    {
        $acl = new \Zend_Acl();
        //Add Roles
        $acl->addRole(self::ROLE_FACILITATOR);
        $acl->addRole(self::ROLE_COORDINATOR, self::ROLE_FACILITATOR);
        $acl->addRole(self::ROLE_ADMIN);
        //Add Resources
        $acl->addResource(self::RESOURCE_ADMIN);
        $acl->addResource(self::RESOURCE_PRESENTATION);
        $acl->addResource(self::RESOURCE_SEARCH);
        $acl->addResource(self::RESOURCE_MEMBER, self::RESOURCE_ADMIN);
        $acl->addResource(self::RESOURCE_USER, self::RESOURCE_ADMIN);
        $acl->addResource(self::RESOURCE_SCHOOL, self::RESOURCE_ADMIN);
        //Establish Rules
        $acl->allow(self::ROLE_ADMIN);
        $acl->allow(self::ROLE_FACILITATOR, self::RESOURCE_PRESENTATION);
        $acl->allow(self::ROLE_FACILITATOR, self::RESOURCE_SEARCH);
        return $acl;
    }
    public static function getAvailableRoles()
    {
        $reflected = new \ReflectionClass(get_called_class());
        $roles = array();
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
