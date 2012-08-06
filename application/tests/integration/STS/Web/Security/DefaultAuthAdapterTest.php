<?php
use STS\Web\Security\DefaultAuthAdapter;
use STS\Core\User\UserDTO;
use STS\Core;

class DefaultAuthAdapterTest extends PHPUnit_Framework_TestCase
{
    const BASIC_USER_NAME = 'muser';
    const BASIC_USER_PASSWORD = 'abc123';
    const BAD_BASIC_USER_NAME = 'buser';
    const BAD_BASIC_USER_PASSWORD = 'badpass';
    /**
     * @test
     */
    public function successfullAuthentication()
    {
        $authFacade = Core::getDefaultInstance()->load('AuthFacade');
        $defaultAuthAdapter = new DefaultAuthAdapter(self::BASIC_USER_NAME, self::BASIC_USER_PASSWORD, $authFacade);
        $authResult = $defaultAuthAdapter->authenticate();
        $this->assertInstanceOf('\Zend_Auth_Result', $authResult);
        $this->assertInstanceOf('STS\Core\User\UserDTO', $authResult->getIdentity());
        $this->assertEquals(\Zend_Auth_Result::SUCCESS, $authResult->getCode());
    }
    /**
     * @test
     */
    public function failedAuthenticationOnUser()
    {
        $authFacade = Core::getDefaultInstance()->load('AuthFacade');
        $defaultAuthAdapter = new DefaultAuthAdapter(self::BAD_BASIC_USER_NAME, self::BASIC_USER_PASSWORD, $authFacade);
        $authResult = $defaultAuthAdapter->authenticate();
        $this->assertInstanceOf('\Zend_Auth_Result', $authResult);
        $this->assertNull($authResult->getIdentity());
        $this->assertEquals(\Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, $authResult->getCode());
    }
    /**
     * @test
     */
    public function failedAuthenticationOnPassword()
    {
        $authFacade = Core::getDefaultInstance()->load('AuthFacade');
        $defaultAuthAdapter = new DefaultAuthAdapter(self::BASIC_USER_NAME, self::BAD_BASIC_USER_PASSWORD, $authFacade);
        $authResult = $defaultAuthAdapter->authenticate();
        $this->assertInstanceOf('\Zend_Auth_Result', $authResult);
        $this->assertNull($authResult->getIdentity());
        $this->assertEquals(\Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, $authResult->getCode());
    }
}
