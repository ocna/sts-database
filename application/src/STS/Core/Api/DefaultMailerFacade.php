<?php
namespace STS\Core\Api;
use STS\Core\Api\MailerFacade;
class DefaultMailerFacade implements MailerFacade
{
    public function sendNewAccountNotification($name, $username, $email, $password)
    {
        var_dump(array($name, $username, $email, $password));
    }

    public static function getDefaultInstance()
    {
        return new DefaultMailerFacade();
    }
}
