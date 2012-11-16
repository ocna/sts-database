<?php
use STS\Core\Service\MessageService\AbstractMessage;
class AbstractMessageTest extends \PHPUnit_Framework_TestCase{

    const VALID_SUBJECT = 'A message for you';
    const VALID_BODY = 'A textual message that is of a resonable length.';

    /**
     * @test
     */
    public function itShouldAcceptSubjectAndMessageInConstructor(){
        $message = new AbstractMessage(self::VALID_SUBJECT, self::VALID_BODY);
        $this->assertInstanceOf('STS\Core\Service\MessageService\AbstractMessage', $message, __METHOD__);
        $this->assertEquals(self::VALID_SUBJECT, $message->getSubject(), 'getSubject');
        $this->assertEquals(self::VALID_BODY, $message->getBody(), 'getBody');
    }
    
}
