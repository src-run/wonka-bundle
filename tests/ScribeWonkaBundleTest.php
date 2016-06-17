<?php

/*
 * This file is part of the `src-run/wonka-bundle` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 * (c) Scribe Inc      <scr@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\WonkaBundle\Tests;

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
            if (true !== static::$container->hasParameter('kernel.cache_dir')) {
                return null;
            }
            $directoryPath = static::$container->getParameter('kernel.cache_dir');
            if (true !== is_dir($directoryPath)) {
                return null;
            }
        }

        foreach ((array) glob($directoryPath.'/*') as $file) {
            is_dir($file) ? $this->clearKernelCacheDirectory($file) : unlink($file);
        }

        rmdir($directoryPath);

        return $this;
    }
}

/* EOF */
