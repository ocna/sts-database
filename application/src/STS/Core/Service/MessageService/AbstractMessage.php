<?php
namespace STS\Core\Service\MessageService;
class AbstractMessage{

    private $subject;
    private $body;

    public function __construct($subject, $body){
        $this->subject = $subject;
        $this->body = $body;
    }

    public function getSubject(){
        return $this->subject;
    }

    public function getBody(){
        return $this->body;
    }
}

