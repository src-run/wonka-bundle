<?php

/*
 * This file is part of the Wonka Bundle.
 *
 * (c) Scribe Inc.     <scr@src.run>
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Tests\Component\DependencyInjection\Compiler\Attendant;

use Scribe\WonkaBundle\Tests\Component\DependencyInjection\Compiler\Fixture\FixtureCompilerAttendant;
use Scribe\WonkaBundle\Utility\TestCase\WonkaTestCase;

/**
 * Class CompilerAttendantTest.
 */
class CompilerAttendantTest extends WonkaTestCase
{
    public function test_can_determine_type_short()
    {
        $a = new FixtureCompilerAttendant();
        $expected = 'FixtureCompilerAttendant';

        self::assertEquals($expected, $a->getType());
        self::assertEquals($expected, $a->getType(false));
        self::assertNotEquals($expected, $a->getType(true));
    }

    public function test_can_determine_type_long()
    {
        $a = new FixtureCompilerAttendant();
        $expected = 'Scribe\WonkaBundle\Tests\Component\DependencyInjection\Compiler\Fixture\FixtureCompilerAttendant';

        self::assertNotEquals($expected, $a->getType());
        self::assertNotEquals($expected, $a->getType(false));
        self::assertEquals($expected, $a->getType(true));
    }
}

/* EOF */
