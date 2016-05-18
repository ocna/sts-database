<?php
/**
 * Sends emails using the SparkPost service
 *
 * @category STS
 * @package Core
 * @subpackage Service
 */
namespace STS\Core\Service;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use STS\Core\Service\EmailMessageService;
use STS\Core\Service\MessageService\MessageServiceException;
use STS\Core\Service\MessageService\EmailMessage;
use SparkPost\Transmission;
use Zend_Registry;

class SparkPostEmailMessageService implements EmailMessageService
{
    private $sourceEmailAddress;
    private $testEmailAddress;

    /**
     *
     * @param string $sourceEmailAddress
     * @param string $testEmailAddress
     */
    public function __construct($sourceEmailAddress, $testEmailAddress)
    {
        if (null == $sourceEmailAddress || null == $testEmailAddress) {
            throw new InvalidArgumentException('Must have email addresses in configuration.');
        }
        
        $this->sourceEmailAddress = $sourceEmailAddress;
        $this->testEmailAddress = $testEmailAddress;
    }
    /**
     *
     *
     * @param  EmailMessage             $message
     * @param  string                   $email
     * @return bool
     * @throws \InvalidArgumentException
     * @throws MessageServiceException
     */
    public function sendMessageToEmail($message, $email)
    {
        $config = Zend_Registry::get('config');
        if ('dev' == $config->env) {
            $email = $this->testEmailAddress;
        }
        if (!$message instanceof EmailMessage) {
            throw new \InvalidArgumentException('Must provide instance of EmailMessage');
        }

        $validator = new \Zend_Validate_EmailAddress();
        if (! $validator->isValid($email)) {
            throw new \InvalidArgumentException('Must provide a valid email address' . $email);
        }

        try {
            Transmission::send(array(
                "from"=>"STS Database <{$this->sourceEmailAddress}>",
                "text" => $message->getBody(),
                "subject"=> $message->getSubject(),
                "trackClicks"=>false,
                "recipients"=>array(
                    array(
                        "address"=>array(
                            "email"=>$email
                        )
                    )
                )
            ));
        } catch (\Exception $exception) {
            throw new MessageServiceException(
                'Error occurred while sending message: ' . $exception->getMessage(),
                $exception->getCode()
            );
        }

        return true;
    }
}
