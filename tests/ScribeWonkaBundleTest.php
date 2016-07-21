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

use Symfony\Component\DependencyInjection\ContainerInterface;
use SR\WonkaBundle\Test\KernelTestCase;

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
