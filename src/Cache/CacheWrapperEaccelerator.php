<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Cache;

/**
 * Class Cache Wrapper for Eaccelerator cache
 *
 * @author Romain Cottard
 */
class CacheWrapperEaccelerator extends CacheWrapperAbstract
{
    /**
     * Clear Cache.
     *
     * @return boolean
     */
    public function clear()
    {
        $keys = eaccelerator_list_keys();
        if (is_array($keys)) {
            foreach ($keys as $info) {
                // eaccelerator bug (Http://eaccelerator.net/ticket/287)
                if (0 === strpos($info['name'], ':')) {
                    $key = substr($info['name'], 1);
                } else {
                    $key = $info['name'];
                }

                if (!eaccelerator_rm($key)) {
                    return false;
                }
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

        return unserialize(eaccelerator_get($this->prefix() . $key));
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

        return (null !== eaccelerator_get($this->prefix() . $key));
    }

    /**
     * Remove Cache content.
     *
     * @param string $key Cache Key
     * @return boolean
     */
    public function remove($key)
    {
        if (!$this->isEnabled() || !$this->has($key)) {
            return false;
        }

        eaccelerator_rm($this->prefix() . $key);

        return true;
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

        return eaccelerator_put($this->prefix() . $key, serialize($value), $lifeTime);
    }
}