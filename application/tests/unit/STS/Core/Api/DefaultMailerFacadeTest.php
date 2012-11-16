<?php
namespace STS\Core\Api;

use STS\Core\Api\DefaultMailerFacade;
use STS\Core\Service\MandrillEmailMessageService;

class DefaultMailerFacadeTest extends \PHPUnit_Framework_TestCase
{
    const VALID_NAME = 'User Name';
    const VALID_USERNAME = 'muser';
    const VALID_EMAIL = 'test.user@email.com';
    const VALID_PASSWORD = 'abc123AD';

    /**
     * @test
     */
    public function validConstructionWithEmailService()
    {
        $messageService = \Mockery::mock('STS\Core\Service\MandrillEmailMessageService');
        $facade = new DefaultMailerFacade($messageService);
        $this->assertInstanceOf('STS\Core\Api\DefaultMailerFacade', $facade);
    }

    /**
     * @test
     * @dataProvider badConstructorArgs
     * @expectedException \InvalidArgumentException
     * @expectedExcpetionMessage Instance of EmailMessageService not provided.
     */
    public function throwExcpetionForNotPassingMessageService($arg)
    {
        $facade = new DefaultMailerFacade($arg);
    }

    public function badConstructorArgs()
    {
        return array(
            array(''),
            array(null),
            array(new \stdClass()),
            array('string')
            );
    }

    /**
     * @test
     */
    public function validSendNewAccountNotification()
    {
        $messageService = \Mockery::mock('STS\Core\Service\MandrillEmailMessageService');
        $messageService->shouldReceive('sendMessageToEmail')->once()->andReturn(true);
        $facade = new DefaultMailerFacade($messageService);
        $results = $facade->sendNewAccountNotification(self::VALID_NAME, self::VALID_USERNAME, self::VALID_EMAIL, self::VALID_PASSWORD);
        $this->assertTrue($results);
    }
}
