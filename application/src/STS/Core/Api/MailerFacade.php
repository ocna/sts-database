<?php
namespace STS\Core\Api;

interface MailerFacade
{
    public function sendNewAccountNotification($name, $username, $email, $password);
}
