<?php

namespace BirknerAlex\RedisSessionHandlerBundle\Session\Storage\Handler;

use SessionHandlerInterface;

/**
 * RedisSessionHandler.
 *
 * Redis based session storage handler based on the PhpRedis.
 *
 * @see https://github.com/phpredis/phpredis
 *
 * @author BirknerAlex <alex.birkner@gmail.com>
 */
class RedisSessionHandler implements SessionHandlerInterface
{
    /**
     * @var \Redis PhpRedis driver.
     */
    private $redis;

    /**
     * @var integer Time to live in seconds
     */
    private $ttl;

    /**
     * @var string Key prefix for shared environments.
     */
    private $prefix;

    /**
     * Constructor.
     *
     * List of available options:
     *  * prefix: The prefix to use for the memcached keys in order to avoid collision
     *  * expiretime: The time to live in seconds
     *
     * @param \Redis $redis A \Redis instance
     * @param array      $options   An associative array of Redis options
     *
     * @throws \InvalidArgumentException When unsupported options are passed
     */
    public function __construct(\Redis $redis, array $options = array())
    {
        $this->redis = $redis;

        if ($diff = array_diff(array_keys($options), array('prefix', 'expiretime'))) {
            throw new \InvalidArgumentException(sprintf(
                'The following options are not supported "%s"', implode(', ', $diff)
            ));
        }

        $this->ttl = isset($options['expiretime']) ? (int) $options['expiretime'] : 86400;
        $this->prefix = isset($options['prefix']) ? $options['prefix'] : 'sf2s';
    }

    /**
     * {@inheritDoc}
     */
    public function open($savePath, $sessionName)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function read($sessionId)
    {
        return $this->redis->get($this->prefix.$sessionId) ?: '';
    }

    /**
     * {@inheritDoc}
     */
    public function write($sessionId, $data)
    {
        return $this->redis->setex($this->prefix.$sessionId, $this->ttl, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function destroy($sessionId)
    {
        return ($this->redis->delete($this->prefix.$sessionId) === 1);
    }

    /**
     * {@inheritDoc}
     */
    public function gc($lifetime)
    {
        // not required here because redis will auto expire the records anyhow.
        return true;
    }
}