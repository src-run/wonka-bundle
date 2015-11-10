<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Tests;

use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ScribeWonkaBundleTest.
 */
class ScribeWonkaBundleTest extends PHPUnit_Framework_TestCase
{
    public static $container;

    public function setUp()
    {
        $kernel = new \AppKernel('test', true);
        $kernel->boot();

        static::$container = $kernel->getContainer();
    }

    public function testCanBuildContainer()
    {
        static::assertTrue(static::$container instanceof ContainerInterface);
    }

    public function tearDown()
    {
        if (false === (static::$container instanceof ContainerInterface)) {
            return $this;
        }

        $this->clearKernelCacheDirectory();

        return $this;
    }

    public function clearKernelCacheDirectory($directoryPath = null)
    {
        if ($directoryPath === null) {
            if (true !== static::$container->hasParameter('kernel.cache_dir')) { return; }
            $directoryPath = static::$container->getParameter('kernel.cache_dir');
            if (true !== is_dir($directoryPath)) { return; }
        }

        foreach ((array) glob($directoryPath . '/*') as $file) {
            is_dir($file) ? $this->clearKernelCacheDirectory($file) : unlink($file);
        }

        rmdir($directoryPath);

        return $this;
    }
}

/* EOF */
