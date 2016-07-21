<?php

/*
 * This file is part of the `src-run/wonka-bundle` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\WonkaBundle\Tests\Utility\Config;

use SR\WonkaBundle\Test\KernelTestCase;
use SR\WonkaBundle\Utility\Path\Path;

class PathTest extends KernelTestCase
{
    private static $fixturesPath = __DIR__.DIRECTORY_SEPARATOR.'Fixtures'.DIRECTORY_SEPARATOR;

    public function setUp()
    {
        parent::setUp();

        static::setUpFixturePaths();
    }

    public function tearDown()
    {
        parent::tearDown();

        static::tearDownFixturePaths();
    }

    protected static function setUpFixturePaths()
    {
        @mkdir(static::$fixturesPath);

        foreach (range(0, 100) as $i) {
            @mkdir($sub = static::$fixturesPath.$i.DIRECTORY_SEPARATOR);

            foreach (range(0, 100) as $j) {
                @touch($sub.$j.'.txt');
            }
        }
    }

    protected static function tearDownFixturePaths()
    {
        $removeInner = function ($basePath, $root) use (&$removeInner) {
            foreach (scandir($basePath, SCANDIR_SORT_NONE) as $name) {
                if ($name === '.' || $name === '..') {
                    continue;
                }

                $path = $basePath.DIRECTORY_SEPARATOR.$name;

                is_dir($path) ? $removeInner($path, false) : unlink($path);
            }

            if ($root === false) {
                rmdir($basePath);
            }
        };

        if (is_dir(static::$fixturesPath)) {
            $removeInner(static::$fixturesPath, true);
        }
    }

    public function testRemove()
    {
        $this->assertTrue(is_dir(static::$fixturesPath));
        $this->assertCount(103, scandir(static::$fixturesPath));

        Path::remove(__DIR__.DIRECTORY_SEPARATOR.'Fixtures');

        $this->assertTrue(is_dir(static::$fixturesPath));
        $this->assertCount(2, scandir(static::$fixturesPath));
    }

    public function testRemoveRoot()
    {
        $this->assertTrue(is_dir(static::$fixturesPath));
        $this->assertCount(103, scandir(static::$fixturesPath));

        Path::remove(__DIR__.DIRECTORY_SEPARATOR.'Fixtures', true);

        $this->assertFalse(is_dir(static::$fixturesPath));
    }
}

/* EOF */
