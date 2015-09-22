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
use Scribe\WonkaBundle\DataFixtures\Locator\DataFixturesLocator;

/**
 * Class ControllerBehaviorsTest.
 */
class DataFixturesLocatorTest extends WonkaTestCase
{
    public function testPathStacking()
    {
        $expected = [
            0 => 'one',
            1 => 'two',
            2 => 'three',
            3 => 'four.1',
            4 => 'four.2',
            5 => 'five.2',
            6 => 'six',
            7 => 'seven',
        ];

        $givenPaths = [
            3 => 'two',
            2 => 'four.2',
            'other' => 'five.2',
            1 => 'three',
            0 => 'one',
            'something' => 'six',
            8 => 'seven',
            4 => 'four.1',
        ];

        $locator = new DataFixturesLocator($givenPaths);

        static::assertEquals($expected, $locator->getPaths());

        $expected = [
            0 => 'one',
            1 => 'two',
            2 => 'three',
            3 => 'four.1',
            4 => 'five.1',
            5 => 'four.2',
            6 => 'five.2',
            7 => 'six',
            8 => 'seven',
        ];

        $givenPath = [5 => 'five.1'];

        $locator->addPath('five.1', 5);

        static::assertEquals($expected, $locator->getPaths());
        static::assertEquals([], $locator->getValidPathCollection());
        static::assertEquals(false, $locator->getFirstValidPath());
        static::assertEquals([], $locator->getValidPathCollection(false));
        static::assertEquals(false, $locator->getFirstValidPath(false));
    }

    public function testValidAndInvalidPaths()
    {
        $expected = [
            0 => './',
            1 => '/more',
            2 => './this-does-not-exist',
            3 => './app/config',
            4 => './bin',
        ];

        $locator = new DataFixturesLocator();

        $locator
            ->addPath('./this-does-not-exist')
            ->addPath('./', 0)
            ->addPath('./app/config', 10)
            ->addPath('./bin', 3)
            ->addPath('/more', 2)
        ;

        static::assertEquals($expected, $locator->getPaths());

        $expected = [
            './',
            './app/config',
            './bin',
        ];

        static::assertEquals($expected, $locator->getValidPathCollection(false));
        static::assertEquals('./', $locator->getFirstValidPath(false));
        static::assertInstanceOf('Symfony\Component\Finder\Finder', $locator->getValidPathCollection());
        static::assertInstanceOf('Symfony\Component\Finder\Finder', $locator->getFirstValidPath());
    }
}

/* EOF */
