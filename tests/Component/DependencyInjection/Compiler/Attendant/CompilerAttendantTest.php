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

namespace SR\WonkaBundle\Tests\Component\DependencyInjection\Compiler\Attendant;

use SR\WonkaBundle\Tests\Component\DependencyInjection\Compiler\Fixture\FixtureCompilerAttendant;
use SR\WonkaBundle\Utility\TestCase\WonkaTestCase;

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
        $expected = 'SR\WonkaBundle\Tests\Component\DependencyInjection\Compiler\Fixture\FixtureCompilerAttendant';

        self::assertNotEquals($expected, $a->getType());
        self::assertNotEquals($expected, $a->getType(false));
        self::assertEquals($expected, $a->getType(true));
    }
}

/* EOF */
