<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Cache;

/**
 * Class Cache Wrapper for php file cache
 *
 * @author Romain Cottard
 */
class CacheWrapperFile extends CacheWrapperAbstract
{
    /**
     * @var array $cache List of Cache keys/files
     */
    protected $cache = array();

    /**
     * @var string $directory Directory where are Cache files.
     */
    protected $directory = '/tmp/Eureka/cache/';

    /**
     * Clean all caches.
     *
     * @return boolean true if ok, false otherwise
     */
    public function clear()
    {
        if (empty($this->cache)) {
            $file = $this->directory . 'Cache.dat';

            //~ Retrieve Cache information.
            if (file_exists($file)) {
                $content = file_get_contents($file);
                if (!empty($content)) {
                    $this->cache = unserialize($content);
                } else {
                    $this->cache = array();
                }
            }
        }

        //~ Clean all caches have expired.
        foreach ($this->cache as $pKey => $time) {
            $this->remove($pKey);
        }

        return true;
    }

    /**
     * Clean Cache have expired.
     *
     * @return boolean
     */
    protected function clearExpired()
    {
        if (empty($this->cache)) {
            $file = $this->directory . 'Cache.dat';

            //~ Retrieve Cache information.
            if (file_exists($file)) {
                $content = file_get_contents($file);
                if (!empty($content)) {
                    $this->cache = unserialize($content);
                } else {
                    $this->cache = array();
                }
            }
        }

        // Clean all caches have expired.
        foreach ($this->cache as $pKey => $time) {
            if ($time < time()) {
                $this->remove($pKey);
            }
        }

        return true;
    }

    /**
     * Gets a value in the Cache for the specified key.
     *
     * @param string $key The key name
     * @return mixed Cache content (null if the key does not exist).
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            return null;
        }

        $file = $this->directory . substr(md5($this->prefix() . $key), 0, 10) . '.dat';

        return unserialize(file_get_contents($file));
    }

    /**
     * Returns true if a given key exists in the Cache, false otherwise.
     *
     * @param string $key The key name
     * @return boolean
     */
    public function has($key)
    {
        //~ Check if enabled
        if (!$this->isEnabled()) {
            return false;
        }

        //~ Clear expired Cache files
        $this->clearExpired();

        $key = $this->prefix() . $key;

        return isset($this->cache[$key]);
    }

    /**
     * Remove Cache content.
     *
     * @param string $key Cache Key
     * @return boolean
     */
    public function remove($key)
    {
        //~ Check if enabled
        if (!$this->isEnabled()) {
            return false;
        }

        $file = $this->directory . substr(md5($key), 0, 10) . '.dat';
        if (!file_exists($file)) {
            return false;
        }

        unlink($file);
        unset($this->cache[$key]);
        $this->save();

        return true;
    }

    /**
     * Save cache information.
     *
     * @return void
     */
    protected function save()
    {
        $file = $this->directory . 'Cache.dat';
        file_put_contents($file, serialize($this->cache));
    }

    /**
     * Sets a value in the Cache for the specified key.
     *
     * @param string  $key The key name
     * @param mixed   $value The content to put in Cache
     * @param integer $lifeTime Time to keep the content in Cache in seconds
     * @return boolean
     */
    public function set($key, $value, $lifeTime = 900)
    {
        //~ Check if enabled
        if (!$this->isEnabled()) {
            return false;
        }

        //~ Clear expired Cache files
        $this->clearExpired();

        $pKey = $this->prefix() . $key;
        $file = $this->directory . substr(md5($pKey), 0, 10) . '.dat';
        if ((bool) file_put_contents($file, serialize($value))) {
            $this->cache[$pKey] = time() + $lifeTime;
            $this->save();

            return true;
        } else {
            return false;
        }
    }

    /**
     * Set directory where Cache is stored.
     * If empty, set to '/tmp/'
     *
     * @param string $directory Cache Directory
     * @return boolean
     */
    public function setDirectory($directory = '/tmp/eureka.cache')
    {
        $status = true;
        if (!empty($directory)) {

            $directory = rtrim($directory, '/');
            if (!is_dir($directory)) {
                $status = mkdir($directory, 0755, true);
            }

            $this->directory = $directory . '/';
        } else {
            $this->directory = '/tmp/';
        }

        return $status;
    }
}
