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

namespace SR\WonkaBundle\Tests\Component\HttpKernel\Bundle;

use SR\WonkaBundle\Component\HttpKernel\Bundle\BundleInspect;
use SR\WonkaBundle\Test\TestCase;

/**
 * Class BundleInspectTest.
 */
class BundleInspectTest extends TestCase
{
    public function testGetContextFromNamespace()
    {
        list($namespaceRoot, $servicePrefix, $serviceBundle)
            = BundleInspect::getContextFromNamespace(__NAMESPACE__);

        $this->assertSame('\SR\WonkaBundle', $namespaceRoot);
        $this->assertSame('wonka_bundle', $serviceBundle);
        $this->assertSame('sr', $servicePrefix);
    }
}

/* EOF */
