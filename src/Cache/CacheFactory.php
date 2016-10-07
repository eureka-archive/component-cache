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
 */
class CacheFactory
{
    /**
     * @var CacheWrapperAbstract[] $instances Array of engines already instantiates
     */
    protected static $instances = array();

    /**
     * @var string[] $engines Array of engines names
     */
    protected static $engines = array(
        'Memcache' => 'memcache_set', 'Apc' => 'apc_store', 'XCache' => 'xcache_set', 'Eaccelerator' => 'Eaccelerator', 'File' => null,
    );

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

        if (empty(static::$instances[$engine])) {
            $class                 = $namespace . '\\' . 'CacheWrapper' . $engine;
            static::$instances[$engine] = new $class();
        }

        return static::$instances[$engine];
    }
}