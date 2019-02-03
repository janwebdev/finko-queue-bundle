<?php

namespace Finko\QueueBundle\Connector;

use Finko\QueueBundle\Type\NullQueue;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class NullConnector
 *
 * @package Finko\QueueBundle\Connectors
 */
class NullConnector implements ConnectorInterface
{
    /**
     * @inheritDoc
     */
    public function connect(ContainerInterface $container, array $config)
    {
        return new NullQueue;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'null';
    }
}