<?php

namespace Finko\QueueBundle;

use Finko\QueueBundle\Job\JobsInterface;

/**
 * Class InteractsWithQueueInterface
 *
 * @package Finko\QueueBundle
 */
interface QueueableInterface
{
    /**
     * @param JobsInterface $job
     * @param mixed         $data
     */
    public function fire(JobsInterface $job, $data = null);
}