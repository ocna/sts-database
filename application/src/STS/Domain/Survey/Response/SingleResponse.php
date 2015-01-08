<?php
namespace STS\Domain\Survey\Response;

use STS\Domain\Survey\AbstractResponse;

class SingleResponse extends AbstractResponse
{
    protected $responseValue;

    public function __construct($responseValue)
    {
        $this->responseValue = $responseValue;
    }

    public function getResponse()
    {
        return $this->responseValue;
    }
}
