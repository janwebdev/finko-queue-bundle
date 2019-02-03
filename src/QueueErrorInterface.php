<?php

namespace Finko\QueueBundle;

/**
 * Class CanBeQueuedWithErrorHandlerInterface
 *
 * @package Finko\QueueBundle
 */
interface QueueErrorInterface
{
    /**
     * @param \Exception $e
     * @param mixed      $payload
     */
    public function failed(\Exception $e, $payload = null);
}