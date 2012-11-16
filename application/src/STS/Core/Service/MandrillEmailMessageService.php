<?php
/**
 * Sends emails using the Mandrill service
 *
 * @category STS
 * @package Core
 * @subpackage Service
 */
namespace STS\Core\Service;

use STS\Core\Service\EmailMessageService;
use STS\Core\Service\MessageService\MessageServiceException;
use STS\Core\Service\MessageService\EmailMessage;

class MandrillEmailMessageService implements EmailMessageService
{
    private $mandrill;
    private $sourceEmailAddress;
    /**
     *
     *
     * @param Mandrill $mandrill
     * @param string    $sourceEmailAddress
     */
    public function __construct($mandrill, $sourceEmailAddress)
    {
        if (!$mandrill instanceof \Mandrill_Messages) {
            throw new \InvalidArgumentException('Requires Configured Mandrill_Messages');
        }
        $this->mandrill = $mandrill;
        $this->sourceEmailAddress = $sourceEmailAddress;
    }
    /**
     *
     *
     * @param  EmailMessage             $message
     * @param  string                   $email
     * @return bool
     * @throws InvalidArgumentException
     * @throws MessageServiceException
     */
    public function sendMessageToEmail($message, $email)
    {
        if (!$message instanceof EmailMessage) {
            throw new \InvalidArgumentException('Must provide instance of EmailMessage');
        }

        $validator = new \Zend_Validate_EmailAddress();
        if (! $validator->isValid($email)) {
            throw new \InvalidArgumentException('Must provide a valid email address');
        }

        $params = array(
            'text' => $message->getBody(),
            'subject' => $message->getSubject(),
            'from_email' => $this->sourceEmailAddress,
            'from_name' => 'STS Database',
            'to' => array(
                array('email' => $email)
                )
            );
        $response = $this->mandrill->send($params);
        if (isset($response['status']) && $response['status'] == 'error') {
            throw new MessageServiceException('Error occured while sending message: ' . $response['message'], $response['code']);
        } else {
            return true;
        }
    }
}
