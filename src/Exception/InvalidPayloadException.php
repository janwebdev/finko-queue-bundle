<?php

namespace Finko\QueueBundle\Exception;

/**
 * Class InvalidPayloadException
 *
 * @package Finko\QueueBundle\Exceptions
 */
class InvalidPayloadException extends \InvalidArgumentException
{
    /**
     * Create a new exception instance.
     *
     * @param  string|null $message
     */
    public function __construct($message = null)
    {
        parent::__construct($message ?: json_last_error());
    }
}