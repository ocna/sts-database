<?php
namespace STS\Core\Service;
use STS\Core\Service\MessageService;
use STS\Core\Service\MessageService\MessageServiceException;
use STS\Core\Service\MessageService\EmailMessage;
use STS\Domain\User;

class AmazonEmailMessageService implements MessageService{

    private $amazonSes;
    private $sourceEmailAddress;

    public function __construct($amazonSes, $sourceEmailAddress){
        if(! $amazonSes instanceof \AmazonSES){
            throw new \InvalidArgumentException('Requires Configured AmazonSES');
        }
        $this->amazonSes = $amazonSes;
        $this->sourceEmailAddress = $sourceEmailAddress;
    }

    public function sendMessageToUser($message, $user){
        if(! $message instanceof EmailMessage){
            throw new \InvalidArgumentException('Must provide instance of EmailMessage');
        }
        if(! $user instanceof User){
            throw new \InvalidArgumentException('Must provide instance of User');
        }
        $to = array(
            'ToAddresses' => array(
                $user->getEmail()
            )
        );
        $content = array( 
            'Subject.Data' => $message->getSubject(),
            'Body.Text.Data' => $message->getBody()
        );
        $response = $this->amazonSes->send_email($this->sourceEmailAddress, $to, $content);

        if(! $response->isOk()){
            throw new MessageServiceException('Error occured while sending message.', $response->status);
        }else{
            return true;
        }
    }
}

