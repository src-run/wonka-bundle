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

namespace SR\WonkaBundle\Tests\Component\DependencyInjection\Compiler\Registrar;

use SR\WonkaBundle\Test\KernelTestCase;
use SR\WonkaBundle\Tests\Component\DependencyInjection\Compiler\Fixtures\FixtureCompilerAttendant;
use SR\WonkaBundle\Tests\Component\DependencyInjection\Compiler\Fixtures\FixtureCompilerRegistrar;

/**
 * Class CompilerRegistrarTest.
 */
class CompilerRegistrarTest extends KernelTestCase
{
    public function testSimpleAttendantRegistration()
    {
        $a0 = new FixtureCompilerAttendant();
        $r0 = new FixtureCompilerRegistrar();

        self::assertCount(0, $r0->getAttendantCollection());
        self::assertFalse($r0->hasAttendant($a0));

        $r0->addAttendant($a0);

        self::assertCount(1, $r0->getAttendantCollection());
        self::assertTrue($r0->hasAttendant($a0));
    }

    public function testPriorityAttendantRegistration()
    {
        $a0 = new FixtureCompilerAttendant();
        $a1 = new FixtureCompilerAttendant();
        $a2 = new FixtureCompilerAttendant();
        $r0 = new FixtureCompilerRegistrar();

        self::assertCount(0, $r0->getAttendantCollection());
        self::assertFalse($r0->hasAttendant($a0));

        $r0->addAttendant($a0, 99);

        self::assertCount(1, $r0->getAttendantCollection());
        self::assertTrue($r0->hasAttendant($a0));

        $r0->addAttendant($a1, 10);

        self::assertCount(2, $r0->getAttendantCollection());
        self::assertTrue($r0->hasAttendant($a1));

        $r0->addAttendant($a2, 20);

        self::assertCount(3, $r0->getAttendantCollection());
        self::assertTrue($r0->hasAttendant($a2));

        list($av0, $av1, $av2) = array_values($r0->getAttendantCollection());

        self::assertEquals($a0, $av0);
        self::assertEquals($a1, $av1);
        self::assertEquals($a2, $av2);

        list($ak0, $ak1, $ak2) = array_keys($r0->getAttendantCollection());

        self::assertEquals(10, $ak0);
        self::assertEquals(20, $ak1);
        self::assertEquals(99, $ak2);
    }

    public function testIterableAttendantRegistration()
    {
        $a0 = new FixtureCompilerAttendant();
        $a1 = new FixtureCompilerAttendant();
        $a2 = new FixtureCompilerAttendant();
        $r0 = new FixtureCompilerRegistrar();

        $r0->addAttendant($a2, 20);
        $r0->addAttendant($a1, 10);
        $r0->addAttendant($a0, 99);

        $is = $vs = [];

        foreach ($r0 as $i => $v) {
            $is[] = $i;
            $vs[] = $v;
        }

        self::assertEquals(array_keys($r0->getAttendantCollection()), $is);
        self::assertEquals(array_values($r0->getAttendantCollection()), $vs);
    }
}

/* EOF */
