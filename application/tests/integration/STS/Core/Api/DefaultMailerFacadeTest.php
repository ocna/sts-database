<?php
namespace STS\Core\Api;

use STS\Core;
use STS\Core\Api\DefaultMailerFacade;

class DefaultMailerFacadeTest extends \PHPUnit_Framework_TestCase
{
    const VALID_NAME = 'User Name';
    const VALID_USERNAME = 'muser';
    const VALID_EMAIL = 'jason.robertfox@gmail.com';
    const VALID_PASSWORD = 'abc123AD';

    /**
     * @test
     */
    public function validConstructionWithEmailService()
    {
        $facade = $this->loadFacadeInstance();
        $this->assertInstanceOf('STS\Core\Api\DefaultMailerFacade', $facade);
    }

    /**
     * @test
     */
    public function validSendNewNotification()
    {
        $facade = $this->loadFacadeInstance();
        $results = $facade->sendNewAccountNotification(self::VALID_NAME, self::VALID_USERNAME, self::VALID_EMAIL, self::VALID_PASSWORD);
        $this->assertTrue($results);
    }


    private function loadFacadeInstance()
    {
        $core = Core::getDefaultInstance();
        $facade = $core->load('MailerFacade');
        return $facade;
    }
}
