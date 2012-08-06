<?php
namespace STS\Web\Security;
use STS\Core\Api\ApiException;
use STS\Core\Api\DefaultAuthFacade;
use STS\Core;

class DefaultAuthAdapter implements \Zend_Auth_Adapter_Interface
{

    private $userName;
    private $password;
    private $authFacade;
    public function __construct($userName, $password, $authFacade)
    {
        $this->userName = $userName;
        $this->password = $password;
        $this->authFacade = $authFacade;
    }
    public function authenticate()
    {
        try {
            $userDto = $this->authFacade->authenticate($this->userName, $this->password);
            return new \Zend_Auth_Result(\Zend_Auth_Result::SUCCESS, $userDto);
        } catch (ApiException $e) {
            $messages = array(
                $e->getMessage()
            );
            switch ($e->getCode()) {
                case ApiException::FAILURE_CREDENTIAL_INVALID:
                    return new \Zend_Auth_Result(\Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, null, $messages);
                    break;
                case ApiException::FAILURE_USER_NOT_FOUND:
                    return new \Zend_Auth_Result(\Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, null, $messages);
                    break;
                default:
                    return new \Zend_Auth_Result(\Zend_Auth_Result::FAILURE, null, $messages);
            }
        }
    }
}
