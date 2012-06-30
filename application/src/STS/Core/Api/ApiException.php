<?php
namespace STS\Core\Api;
class ApiException extends \Exception
{
    const FAILURE_USER_NOT_FOUND = - 104;
    const FAILURE_CREDENTIAL_INVALID = - 101;
}