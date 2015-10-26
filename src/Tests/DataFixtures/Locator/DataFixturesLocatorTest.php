<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Tests\DataFixtures\Locator;

use Scribe\WonkaBundle\Utility\TestCase\WonkaTestCase;
use Scribe\WonkaBundle\DataFixtures\Locator\FixtureLocator;

/**
 * Class ControllerBehaviorsTest.
 */
class DataFixturesLocatorTest extends WonkaTestCase
{
    public function testAllInvalidPaths()
    {
        $fakePaths = [
            0 => 'one',
            1 => 'two',
            2 => 'three',
            3 => 'four.1',
            4 => 'four.2',
            5 => 'five.2',
            6 => 'six',
            7 => 'seven'
        ];

        $locator = new FixtureLocator();

        static::assertEquals([], $locator->locate('FixtureLocator.php'));
        static::assertEquals([], $locator->locateValidPaths($fakePaths));
    }

    public function testValidAndInvalidPaths()
    {
        $expected = [
            './',
            './app/config',
            './bin',
        ];

        $locator = new FixtureLocator($expected);

        static::assertEquals([], $locator->locate('FixtureLocator.php'));
        static::assertEquals($expected, $locator->locateValidPaths($expected));
    }
}

/* EOF */
