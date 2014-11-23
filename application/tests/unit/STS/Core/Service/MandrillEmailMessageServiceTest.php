<?php
namespace STS\Core\Service;

use STS\Core\Service\MandrillEmailMessageService;
use STS\Core\Service\MessageService\EmailMessage;
use STS\Core\Service\MessageService\MessageServiceException;

class MandrillEmailMessageServiceTest extends \PHPUnit_Framework_TestCase
{

    const VALID_SOURCE_EMAIL = 'tech@ovariancancer.org';
    const VALID_EMAIL = 'success@simulator.amazonses.com';

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Requires Configured Mandrill_Messages
     */
    public function itShouldThrowExceptionForNotGettingMandrill()
    {
        $service = new MandrillEmailMessageService(null, null);
    }

    /**
     * @test
     */
    public function itShouldAcceptAnMandrillAndSource()
    {
        $mandrill = $this->getMockMandrillMessages();
        $service = new MandrillEmailMessageService($mandrill, self::VALID_SOURCE_EMAIL);
        $this->assertInstanceOf('STS\Core\Service\EmailMessageService', $service);
        $this->assertInstanceOf('STS\Core\Service\MandrillEmailMessageService', $service);
    }

    /**
     * @test
     */
    public function itShouldReturnTrueForSuccessfullSendToEmail()
    {
        $mandrill = $this->getMockMandrillMessages();
        $mandrill->shouldReceive('send')->withAnyArgs()->andReturn(array(array('email'=>self::VALID_EMAIL, 'status'=>'sent')));
        $service = new MandrillEmailMessageService($mandrill, self::VALID_SOURCE_EMAIL);
        $message = new EmailMessage('Test', 'Test');
        $this->assertTrue($service->sendMessageToEmail($message, self::VALID_EMAIL));
    }

    /**
     * @test
     * @expectedException STS\Core\Service\MessageService\MessageServiceException
     * @expectedExceptionMessage Error occurred while sending message: Invalid API key
     * @expectedExceptionCode -1
     */
    public function itShouldThrowExceptionOnSendFailure()
    {
        $mandrill = $this->getMockMandrillMessages();
        $mandrill->shouldReceive('send')->withAnyArgs()->andReturn(array('status'=>'error', 'code'=>-1, 'message'=> 'Invalid API key'));
        $service = new MandrillEmailMessageService($mandrill, self::VALID_SOURCE_EMAIL);
        $message = new EmailMessage('Test', 'Test');
        $service->sendMessageToEmail($message, self::VALID_SOURCE_EMAIL);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Must provide instance of EmailMessage
     */
    public function itShouldThrowExceptionForNotMessage()
    {
        $mandrill = $this->getMockMandrillMessages();
        $service = new MandrillEmailMessageService($mandrill, self::VALID_SOURCE_EMAIL);
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
        $service = new MandrillEmailMessageService($mandrill, self::VALID_SOURCE_EMAIL);
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
