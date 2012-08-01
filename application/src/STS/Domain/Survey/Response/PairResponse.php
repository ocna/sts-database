<?php
namespace STS\Domain\Survey\Response;
use STS\Domain\Survey\AbstractResponse;

class PairResponse extends AbstractResponse {

    protected $beforeValue;
    protected $afterValue;
    public function __construct($beforeValue, $afterValue) {
        $this->beforeValue = $beforeValue;
        $this->afterValue = $afterValue;
    }
    public function getBeforeResponse() {
        return $this->beforeValue;
    }
    public function getAfterResponse() {
        return $this->afterValue;
    }
}
