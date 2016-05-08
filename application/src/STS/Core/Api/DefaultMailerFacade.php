<?php
namespace STS\Core\Api;

use STS\Core\Api\MailerFacade;
use SparkPost\SparkPost;
use STS\Core\Service\MessageService\EmailMessage;
use STS\Core\Service\SparkPostEmailMessageService;
use STS\Core\Service\MessageService\MessageServiceException;

class DefaultMailerFacade implements MailerFacade
{
    private $messageService;

    public function __construct($messageService)
    {
        if (! $messageService instanceof \STS\Core\Service\EmailMessageService) {
            throw new \InvalidArgumentException('Instance of EmailMessageService not provided.');
        }
        $this->messageService = $messageService;
    }

    public function sendNewAccountNotification($name, $username, $email, $password)
    {
        try {
            $body = sprintf($this->getNewAccountNotificationTemplate(), $name, $username, $password);
            $subject = 'STS Database User Access Information';
            $message = new EmailMessage($subject, $body);
            return $this->messageService->sendMessageToEmail($message, $email);
        } catch (MessageServiceException $e) {
            throw new ApiException('Unable to send notification', null, $e);
        }
    }

    private function getNewAccountNotificationTemplate()
    {
        return 'Hi %s,

        Your account details for the STS Database have been updated!

        You can now login at http://stsdatabase.org using the following information:

        username: %s
        password: %s

        Thanks!';
    }

    public static function getDefaultInstance($config)
    {
        $emailConfig = $config->modules->default->email->sparkpost;
        SparkPost::setConfig(array('key'=> $emailConfig->api_key));
        $messageService = new SparkPostEmailMessageService($emailConfig->sourceEmailAddress, $emailConfig->testEmailAddress);
        return new DefaultMailerFacade($messageService);
    }
}
