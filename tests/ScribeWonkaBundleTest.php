<?php

/*
 * This file is part of the `src-run/wonka-bundle` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\WonkaBundle\Tests;

use SR\WonkaBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ScribeWonkaBundleTest.
 */
class ScribeWonkaBundleTest extends KernelTestCase
{
    public function testCanBuildContainer()
    {
        static::assertTrue($this->getContainer() instanceof ContainerInterface);
    }
}

/* EOF */
