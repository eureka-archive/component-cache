<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\Cache;

require_once __DIR__.'/../src/Cache/CacheFactory.php';
require_once __DIR__.'/../src/Cache/CacheWrapperAbstract.php';
require_once __DIR__.'/../src/Cache/CacheWrapperFile.php';

/**
 * Class Test for cache
 *
 * @author Romain Cottard
 */
class CacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Cache::*() xcache NOT SUPPORTED BY PHP-CLI
     *
     * @return   void
     * @covers  CacheFactory::build
     * @covers  CacheFactory::engine
     * @covers  CacheWrapperFile::__construct
     * @covers  CacheWrapperFile::get
     * @covers  CacheWrapperFile::has
     * @covers  CacheWrapperFile::set
     * @covers  CacheWrapperFile::setDirectory
     * @covers  CacheWrapperFile::save
     * @covers  CacheWrapperFile::remove
     * @covers  CacheWrapperFile::clearExpired
     */
    public function testFactory()
    {
        $cache = CacheFactory::build('file');

        $this->assertTrue($cache instanceof CacheWrapperFile);

        $cache->setDirectory('eureka.cachetest');

        $cache->set('my.key.1', 'My Value 1', 3);
        $cache->set('my.key.2', 2, 3);
        $cache->set('my.key.3', true, 3);

        $this->assertTrue($cache->has('my.key.1'));
        $this->assertTrue($cache->has('my.key.2'));
        $this->assertTrue($cache->has('my.key.3'));

        $this->assertEquals($cache->get('my.key.1'), 'My Value 1');
        $this->assertEquals($cache->get('my.key.2'), 2);
        $this->assertTrue($cache->get('my.key.3'));

        sleep(5);

        $this->assertTrue(!$cache->has('my.key.1'));
        $this->assertTrue(!$cache->has('my.key.2'));
        $this->assertTrue(!$cache->has('my.key.3'));

        $this->assertEquals($cache->get('my.key.1'), null);

        //~ Set with more lifetime & clear all cache
        $cache->set('my.key.1', 'My Value 1', 3600);
        $cache->set('my.key.2', 2, 3600);
        $cache->set('my.key.3', true, 3600);

        sleep(5);

        $this->assertTrue($cache->has('my.key.1'));
        $this->assertTrue($cache->has('my.key.2'));
        $this->assertTrue($cache->has('my.key.3'));

        $this->assertEquals($cache->get('my.key.1'), 'My Value 1');
        $this->assertEquals($cache->get('my.key.2'), 2);
        $this->assertTrue($cache->get('my.key.3'));

        $cache->clear();

        $this->assertTrue(!$cache->has('my.key.1'));
        $this->assertTrue(!$cache->has('my.key.2'));
        $this->assertTrue(!$cache->has('my.key.3'));

        $this->assertEquals($cache->get('my.key.1'), null);

        unlink('eureka.cachetest/Cache.dat');
        rmdir('eureka.cachetest');
    }

}