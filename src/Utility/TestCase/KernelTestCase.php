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

namespace SR\WonkaBundle\Utility\TestCase;

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
        if (!static::$staticContainer instanceof ContainerInterface) {
            return null;
        }

        $cacheDir = static::$staticContainer->getParameter('kernel.cache_dir');

        $this->removeDirectoryIfExists(realpath($cacheDir));
    }
}

/* EOF */
