<?php
namespace STS\Core\Service;

interface EmailMessageService
{
    public function sendMessageToEmail($message, $email);
}
