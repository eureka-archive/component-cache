<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Cache;

/**
 * Class Cache Wrapper for Memcache cache
 *
 * @author Romain Cottard
 */
class CacheWrapperMemcache extends CacheWrapperAbstract
{
    /**
     * @var \Memcache $server Current server instance
     */
    protected $server = null;

    /**
     * @var \Memcache[] $servers List of servers
     */
    protected $servers = array();

    /**
     * Connect and use specified server.
     *
     * @param  string $host
     * @param  int $port
     * @return bool
     */
    public function connect($host = '127.0.0.1', $port = 11211)
    {
        if (!isset($this->servers[$host . ':' . $port])) {
            $this->server = new \Memcache();
            $this->server->connect($host, $port);

            $this->servers[$host . ':' . $port] = $this->server;
        } else {
            $this->server = $this->servers[$host . ':' . $port];
        }

        return true;
    }

    /**
     * Clear Cache.
     *
     * @return boolean
     */
    public function clear()
    {
        return $this->server->flush();
    }

    /**
     * Get a value in the Cache for the specified key.
     *
     * @param string $key The key name
     * @return mixed Cache content (null if the key does not exist).
     */
    public function get($key)
    {
        if (!$this->isEnabled()) {
            return null;
        }

        $value = $this->server->get($this->prefix . $key);

        return false === $value ? null : $value;
    }

    /**
     * Returns true if a given key exists in the Cache, false otherwise.
     *
     * @param string $key The key name
     * @return boolean
     */
    public function has($key)
    {
        if (!$this->isEnabled()) {
            return false;
        }

        return (false !== $this->server->get($this->prefix . $key));
    }

    /**
     * Remove Cache content.
     *
     * @param string $key Cache Key
     * @return boolean
     */
    public function remove($key)
    {
        if (!$this->isEnabled()) {
            return false;
        }

        return $this->server->delete($this->prefix . $key);
    }

    /**
     * Remove Cache content.
     *
     * @param string $key Cache Key
     * @return boolean
     */
    public function delete($key)
    {
        if (!$this->isEnabled()) {
            return false;
        }

        return $this->server->delete($this->prefix . $key);
    }

    /**
     * Set a value in the Cache for the specified key.
     *
     * @param string  $key The key name
     * @param mixed   $value The content to put in Cache
     * @param integer $lifeTime Time to keep the content in Cache in seconds
     * @return boolean
     */
    public function set($key, $value, $lifeTime = 3600)
    {
        if (!$this->isEnabled()) {
            return false;
        }

        return $this->server->set($this->prefix . $key, $value, false, $lifeTime);
    }
}