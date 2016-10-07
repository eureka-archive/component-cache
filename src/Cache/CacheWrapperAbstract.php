<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Cache;

/**
 * Abstract class Cache Wrapper
 *
 * @author Romain Cottard
 */
abstract class CacheWrapperAbstract
{
    /**
     * @var boolean $enabled If Cache is enabled.
     */
    protected $enabled = true;

    /**
     * @var string $prefix Base prefix (Application Cache prefix).
     */
    protected $prefix = 'ke5e4f5fsj';

    /**
     * @var integer TIME_SECOND One second in seconds.
     */
    const TIME_SECOND = 1;

    /**
     * @var integer TIME_HOUR One minute in seconds.
     */
    const TIME_MINUTE = 60;

    /**
     * @var integer TIME_HOUR One hour in seconds.
     */
    const TIME_HOUR = 3600;

    /**
     * @var integer TIME_DAY One day in seconds.
     */
    const TIME_DAY = 86400;

    /**
     * Class constructor
     *
     * @param string $prefix Prefix for cache name.
     */
    public function __construct($prefix = null)
    {
        $this->prefix($prefix);
    }

    /**
     * Clear Cache.
     *
     * @return boolean
     */
    abstract public function clear();

    /**
     * Clear Cache have been expired.
     *
     * @return   boolean
     */
    protected function clearExpired()
    {
        return true;
    }

    /**
     * Connection to the cache server
     *
     * @param  string $host
     * @param  int    $port
     * @return boolean
     */
    public function connect($host = '', $port = 0)
    {
        return true;
    }

    /**
     * Disable Cache.
     *
     * @return CacheWrapperAbstract
     */
    public function disable()
    {
        $this->enabled = false;

        return $this;
    }

    /**
     * Enable Cache.
     *
     * @return CacheWrapperAbstract
     */
    public function enable()
    {
        $this->enabled = true;

        return $this;
    }

    /**
     * Check if cache is enabled or not.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Gets a value in the Cache for the specified key.
     *
     * @param string $key The key name
     * @return mixed Cache content (null if the key does not exist).
     */
    abstract public function get($key);

    /**
     * Returns true if a given key exists in the Cache, false otherwise.
     *
     * @param string $key The key name
     * @return boolean
     */
    abstract public function has($key);

    /**
     * Define prefix for Cache Application and return it.
     *
     * @param string $prefix Base prefix (Cache Application)
     * @return string Return base prefix.
     */
    public function prefix($prefix = null)
    {
        if (null !== $prefix) {
            $this->prefix = substr(md5($prefix), 0, 10) . '_';
        }

        return $this->prefix;
    }

    /**
     * Remove Cache content.
     *
     * @param string $key Cache Key
     * @return boolean
     */
    abstract public function remove($key);

    /**
     * Sets a value in the Cache for the specified key.
     *
     * @param string  $key The key name
     * @param mixed   $value The content to put in Cache
     * @param integer $lifeTime Time to keep the content in Cache in seconds
     * @return boolean
     */
    abstract public function set($key, $value, $lifeTime = 3600);
}