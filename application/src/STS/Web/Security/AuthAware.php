<?php
namespace STS\Web\Security;
interface AuthAware
{
    public function setAuth(\Zend_Auth $auth);
    public function getAuth();
}
