<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Utility\TestCase;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as BaseKernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class KernelTestCase.
 */
class KernelTestCase extends BaseKernelTestCase
{
    use WonkaTestCaseTrait;

    /**
     * @var bool
     */
    public $populateContainerNonStatic = true;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public static $staticContainer;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public $container;

    public function setUp()
    {
        static::bootKernel();
        static::$staticContainer = static::$kernel->getContainer();

        if (true === $this->populateContainerNonStatic) {
            $this->container = static::$kernel->getContainer();
        }
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function clearKernelCache()
    {
        if (!$this->container instanceof ContainerInterface) {
            return;
        }

        $cacheDir = $this->container->getParameter('kernel.cache_dir');

        $this->removeDirectoryIfExists(realpath($cacheDir));
    }
}

/* EOF */
