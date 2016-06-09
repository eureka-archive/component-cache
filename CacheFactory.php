<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Cache;

/**
 * Class Cache Factory.
 *
 * @author Romain Cottard
 * @version 2.1.0
 */
class CacheFactory
{

    /**
     * Array of engines already instantiates
     *
     * @var array $this
     */
    protected static $this = array();

    /**
     * Array of engines names
     *
     * @var array $engines
     */
    protected static $engines = array('Memcache' => 'memcache_set','Apc' => 'apc_store','XCache' => 'xcache_set','Eaccelerator' => 'Eaccelerator','File' => null);

    /**
     * Get name of php Cache engine used.
     *
     * @return string engine
     */
    protected static function engine()
    {
        $engine = '';

        foreach (static::$engines as $name => $function) {
            if ($function === null || function_exists($function)) {
                $engine = $name;
                break;
            }
        }

        return $engine;
    }

    /**
     * Instantiate Cache Wrapper object & return it.
     *
     * @param string $engine Class name of the engine to use.
     * @param string $namespace
     * @return CacheWrapperAbstract
     *
     */
    public static function build($engine = null, $namespace = '\Eureka\Component\Cache')
    {
        if (null === $engine) {
            $engine = static::engine();
        }

        if (empty(static::$this[$engine])) {
            $class = $namespace . '\\' . 'CacheWrapper' . $engine;
            static::$this[$engine] = new $class();
        }

        return static::$this[$engine];
    }

}