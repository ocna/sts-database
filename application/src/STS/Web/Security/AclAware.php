<?php
namespace STS\Web\Security;

interface AclAware
{
    public function setAcl(\Zend_Acl $acl);
    public function getAcl();
}
