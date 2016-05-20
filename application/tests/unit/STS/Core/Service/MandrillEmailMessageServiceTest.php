<?php
namespace STS\Core\Service;

use Behat\Mink\Exception\Exception;
use STS\Core\Service\SparkPostEmailMessageService;
use STS\Core\Service\MessageService\EmailMessage;
use STS\Core\Service\MessageService\MessageServiceException;
use Zend_Config;
use Zend_Registry;
use SparkPost\SparkPost;

class MandrillEmailMessageServiceTest extends \PHPUnit_Framework_TestCase
{

    const VALID_SOURCE_EMAIL = 'tech@ocrfa.org';
    const VALID_EMAIL = 'success@simulator.amazonses.com';

    public function setUp()
    {
        parent::setUp();
        $config = new Zend_Config(['env' => 'test'], true);
        Zend_Registry::set('config', $config);

        SparkPost::setConfig(array('key'=> '3632f480399310c7a27a03f966568beefc24f7c5'));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Must have email addresses in configuration.
     */
    public function itShouldThrowExceptionForNotGettingSparkPost()
    {
        $service = new SparkPostEmailMessageService(null, null);
    }

    /**
     * @test
     */
    public function itShouldAcceptAnMandrillAndSource()
    {
        $mandrill = $this->getMockMandrillMessages();
        $service = new SparkPostEmailMessageService(self::VALID_SOURCE_EMAIL, self::VALID_EMAIL);
        $this->assertInstanceOf('STS\Core\Service\EmailMessageService', $service);
        $this->assertInstanceOf('STS\Core\Service\SparkPostEmailMessageService', $service);
    }

    /**
     * @test
     */
    public function itShouldReturnTrueForSuccessfullSendToEmail()
    {
        $mandrill = $this->getMockMandrillMessages();
        $mandrill->shouldReceive('send')->withAnyArgs()->andReturn(array(array('email'=>self::VALID_EMAIL, 'status'=>'sent')));
        $service = new SparkPostEmailMessageService(self::VALID_SOURCE_EMAIL, self::VALID_EMAIL);
        $message = new EmailMessage('Test', 'Test');
        $this->assertTrue($service->sendMessageToEmail($message, self::VALID_EMAIL));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Must provide instance of EmailMessage
     */
    public function itShouldThrowExceptionForNotMessage()
    {
        $mandrill = $this->getMockMandrillMessages();
        $service = new SparkPostEmailMessageService(self::VALID_SOURCE_EMAIL, self::VALID_EMAIL);
        $service->sendMessageToEmail(null, null);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Must provide a valid email address
     */
    public function itShouldThrowExceptionForNotEmail()
    {
        $mandrill = $this->getMockMandrillMessages();
        $service = new SparkPostEmailMessageService(self::VALID_SOURCE_EMAIL, self::VALID_EMAIL);
        $message = new EmailMessage('Test', 'Test');
        $service->sendMessageToEmail($message, null);
    }

    private function getMockMandrillMessages()
    {
        $mandrill = \Mockery::mock('Mandrill_Messages');
        //$mandrill->shouldReceive('__destruct')->set('ch', curl_init());
        return $mandrill;
    }
}
