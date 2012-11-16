<?php
use STS\Core\Service\AmazonEmailMessageService;
use STS\Core\Service\MessageService\EmailMessage;
use STS\Domain\User;
use STS\Core\Service\MessageService\MessageServiceException;
class AmazonEmailMessageServiceTest extends \PHPUnit_Framework_TestCase{

const VALID_SOURCE_EMAIL = 'no-reply@sts.ovariancancer.org';

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Requires Configured AmazonSES
     */
    public function itShouldThrowExceptionForNotGettingAmazonSES(){
        $service = new AmazonEmailMessageService(null, null);
    }

    /**
     * @test
     */
    public function itShouldAcceptAnAmazonSESAndSource(){
        $amazonSes = Mockery::mock('AmazonSES');
        $service = new AmazonEmailMessageService($amazonSes, self::VALID_SOURCE_EMAIL);
        $this->assertInstanceOf('STS\Core\Service\MessageService', $service);
        $this->assertInstanceOf('STS\Core\Service\AmazonEmailMessageService', $service);
    }

    /**
     * @test
     */
    public function itShouldReturnTrueForSuccessfullSendToUser(){
        $amazonSes = Mockery::mock('AmazonSES');
        $amazonSes->shouldReceive('send_email')->withAnyArgs()->andReturn(new CFResponse('','',200));
        $service = new AmazonEmailMessageService($amazonSes, self::VALID_SOURCE_EMAIL);
        $message = new EmailMessage('Test', 'Test');
        $user = new User();
        $user->setEmail('member.user@email.com');
        $this->assertTrue($service->sendMessageToUser($message, $user));
    }

    /**
     * @test
     * @expectedException STS\Core\Service\MessageService\MessageServiceException
     * @expectedExceptionMessage Error occured while sending message.
     * @expectedExceptionCode 500
     */
    public function itShouldThrowExceptionOnSendFailure(){
        $amazonSes = Mockery::mock('AmazonSES');
        $amazonSes->shouldReceive('send_email')->withAnyArgs()->andReturn(new CFResponse('','',500));
        $service = new AmazonEmailMessageService($amazonSes, self::VALID_SOURCE_EMAIL);
        $message = new EmailMessage('Test', 'Test');
        $user = new User();
        $user->setEmail('member.user@email.com');
        $service->sendMessageToUser($message, $user);
    }
    
    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Must provide instance of EmailMessage
     */
    public function itShouldThrowExceptionForNotMessage(){
        $amazonSes = Mockery::mock('AmazonSES');
        $service = new AmazonEmailMessageService($amazonSes, self::VALID_SOURCE_EMAIL);
        $service->sendMessageToUser(null, null);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Must provide instance of User
     */
    public function itShouldThrowExceptionForNotUser(){
        $amazonSes = Mockery::mock('AmazonSES');
        $service = new AmazonEmailMessageService($amazonSes, self::VALID_SOURCE_EMAIL);
        $message = new EmailMessage('Test', 'Test');
        $service->sendMessageToUser($message, null);
    }
    
    
    
    
}
