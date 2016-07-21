<?php

/*
 * This file is part of the `src-run/wonka-bundle` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\WonkaBundle\Test;

use SR\WonkaBundle\Utility\Path\Path;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as BaseKernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class KernelTestCase.
 */
class KernelTestCase extends BaseKernelTestCase
{
    use KernelTestCaseTrait;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::bootKernel();

        static::$container = static::$kernel->getContainer();
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        if (static::$container instanceof ContainerInterface) {
            Path::remove(static::$container->getParameter('kernel.cache_dir'));
        }
    }
}

/* EOF */
