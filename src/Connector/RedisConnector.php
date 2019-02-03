<?php

namespace Finko\QueueBundle\Connector;

use IdeasBucket\Common\Utils\ArrayHelper;
use Finko\QueueBundle\Exception\BadConnectionException;
use Finko\QueueBundle\Type\RedisQueue;
use Predis\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RedisConnector
 *
 * @package Finko\QueueBundle\Connectors
 */
class RedisConnector implements ConnectorInterface
{
    /**
     * The Redis database instance.
     *
     * @var Client
     */
    protected $redis;

    /**
     * Create a new Redis queue connector instance.
     *
     * @param  Client  $redis
     */
    public function __construct(Client $redis = null)
    {
        $this->redis = $redis;
    }

    /**
     * Establish a queue connection.
     *
     * @param ContainerInterface $container
     * @param array              $config
     *
     * @throws BadConnectionException
     *
     * @return RedisQueue
     */
    public function connect(ContainerInterface $container, array $config)
    {
        if ($this->redis === null) {

            throw new BadConnectionException('Redis client not configured');
        }

        return (new RedisQueue(
            $this->redis,
            ArrayHelper::get($config, 'queue'),
            ArrayHelper::get($config, 'retry_after', 60)
        ))->setContainer($container);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'redis';
    }


}
