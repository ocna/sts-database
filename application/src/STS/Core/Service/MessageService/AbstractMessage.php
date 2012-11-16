<?php
/**
 *
 * @category STS
 * @package Core
 * @subpackage Service
 */

namespace STS\Core\Service\MessageService;
class AbstractMessage
{
    private $subject;
    private $body;

    /**
     *
     *
     * @param string $subject
     * @param string $body
     */
    public function __construct($subject, $body)
    {
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     *
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     *
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

}
