<?php

namespace Finko\QueueBundle\Connector;

use Finko\QueueBundle\Type\QueueInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Interface ConnectorInterface
 *
 * @package Finko\QueueBundle\Connectors
 */
interface ConnectorInterface
{
    /**
     * Establish a queue connection and return the queue instance.
     *
     * @param  ContainerInterface $container The application container.
     * @param  array              $config    Connection configuration.
     *
     * @return QueueInterface The new queue instance.
     */
    public function connect(ContainerInterface $container, array $config);

    /**
     * Returns the unique name of the connection.
     *
     * @return string
     */
    public function getName();
}