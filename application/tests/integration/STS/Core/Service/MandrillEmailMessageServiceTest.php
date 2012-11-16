<?php
namespace STS\Core\Service;

use STS\Core;
use STS\Core\Service\MessageService\EmailMessage;
use STS\Core\Service\MandrillEmailMessageService;

class MandrillEmailMessageServiceTest extends \PHPUnit_Framework_TestCase
{
    const DELIEVERABLE_EMAIL = 'jason.robertfox@gmail.com';
    const TEST_SUBJECT = 'MandrillEmailMessageServiceTest Integration Test';
    const TEST_BODY = 'This email was sent durring integration testing of the sts database email system.';

    /**
     * @test
     */
    public function validCreateService()
    {
        $configPath = APPLICATION_PATH . Core::CORE_CONFIG_PATH;
        $config = new \Zend_Config_Xml($configPath, 'all');
        $emailConfig = $config->modules->default->email->mandrill;
        $mandrill = new \Mandrill($emailConfig->api_key);
        $this->assertEquals("PONG!", $mandrill->users->ping());
        $service = new MandrillEmailMessageService($mandrill->messages, $emailConfig->sourceEmailAddress);
        $this->assertInstanceOf('STS\Core\Service\MandrillEmailMessageService', $service);
    }

    /**
     * @test
     */
    public function validSendMessageToEmail()
    {
        $configPath = APPLICATION_PATH . Core::CORE_CONFIG_PATH;
        $config = new \Zend_Config_Xml($configPath, 'all');
        $emailConfig = $config->modules->default->email->mandrill;
        $mandrill = new \Mandrill($emailConfig->api_key);
        $service = new MandrillEmailMessageService($mandrill->messages, $emailConfig->sourceEmailAddress);
        $message = new EmailMessage(self::TEST_SUBJECT, self::TEST_BODY);
        $results = $service->sendMessageToEmail($message, self::DELIEVERABLE_EMAIL);
        $this->assertTrue($results);
    }
}
