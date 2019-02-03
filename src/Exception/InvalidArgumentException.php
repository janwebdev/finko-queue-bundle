<?php

namespace Finko\QueueBundle\Exception;

use Psr\Cache\InvalidArgumentException as Psr6CacheInterface;
use Psr\SimpleCache\InvalidArgumentException as SimpleCacheInterface;

/**
 * Class InvalidArgumentException
 *
 * @package Finko\QueueBundle\Exception
 */
class InvalidArgumentException extends \InvalidArgumentException implements Psr6CacheInterface, SimpleCacheInterface
{
}