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

namespace SR\WonkaBundle\Tests\Utility\Config;

use SR\WonkaBundle\Utility\Locator\BundleLocator;
use SR\WonkaBundle\Utility\TestCase\WonkaTestCase;

class BundleLocatorTest extends WonkaTestCase
{
    public function testGetContextFromNamespace()
    {
        list($root, $name, $service) = BundleLocator::getContextFromNamespace(__NAMESPACE__);

        $this->assertSame('SR', $root);
        $this->assertSame('wonka_bundle', $name);
        $this->assertSame('sr.wonka_bundle', $service);
    }
}

/* EOF */
