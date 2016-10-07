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
class CacheWrapperXCache extends CacheWrapperAbstract
{
    /**
     * Clear Cache.
     *
     * @return boolean
     */
    public function clear()
    {
        $max = xcache_count(XC_TYPE_VAR);
        for ($index = 0; $index < $max; $index++) {
            if (!xcache_clear_cache(XC_TYPE_VAR, $index)) {
                return false;
            }
        }

        return true;
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

        if (xcache_isset($this->prefix() . $key)) {
            return unserialize(xcache_get($this->prefix() . $key));
        } else {
            return null;
        }
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

        return (xcache_isset($this->prefix() . $key));
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

        xcache_unset($this->prefix() . $key);
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

        return xcache_set($this->prefix() . $key, serialize($value), $lifeTime);
    }
}