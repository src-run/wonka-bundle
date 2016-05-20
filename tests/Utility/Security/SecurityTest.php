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

namespace SR\WonkaBundle\Tests\Utility\Security;

use SR\WonkaBundle\Utility\Security\Security;
use SR\WonkaBundle\Utility\TestCase\WonkaTestCase;

class SecurityTest extends WonkaTestCase
{
    public function testRandomPassword()
    {
        static::assertTrue(Security::isSecurePassword(Security::getRandomPassword(10)));
        static::assertEquals(12, strlen(Security::getRandomPassword(12)));
        static::assertEquals(100, strlen(Security::getRandomPassword(100)));
    }

    public function testRandomPasswordExceptionTooShort()
    {
        $this->setExpectedException('SR\Exception\RuntimeException');
        Security::getRandomPassword(7);
    }

    public function testIsSecurePassword()
    {
        $passwords = [
            'to',
            'short',
            'contemplation',
            'thisIsBe@#77erAn3ShouldPa33',
            'thisPa33e3CrackLibButDoesNotMeetRegex',
        ];

        $expected = [
            false,
            false,
            false,
            true,
            false,
        ];

        for ($i = 0; $i < count($passwords); ++$i) {
            static::assertEquals($expected[$i], Security::isSecurePassword($passwords[$i]));
        }
    }

    public function testIsSecurePasswordException()
    {
        $this->setExpectedException('SR\Exception\RuntimeException');
        Security::isSecurePassword('nope', '', true);
    }

    public function testRandomBytes()
    {
        $random = [];

        for ($i = 0; $i < 10000; ++$i) {
            $random[] = Security::getRandomBytes(10, true);
            static::assertEquals(10, strlen($random[$i]));
        }

        for ($i = 0; $i < 10000; ++$i) {
            $randomValue = $random[$i];
            unset($random[$i]);
            static::assertFalse(in_array($randomValue, $random));
        }

        $random = [];

        for ($i = 0; $i < 10000; ++$i) {
            $random[] = Security::getRandomBytes(10, false, function ($string) {
                return str_replace('a', '', $string);
            });
            static::assertFalse(strpos($random[$i], 'a'));
        }

        for ($i = 1; $i < 10000; ++$i) {
            static::assertEquals($i, strlen(Security::getRandomBytes($i, true)));
            static::assertEquals($i * 2, strlen(Security::getRandomBytes($i, false)));
        }
    }

    public function testRandomBytesExceptionInvalidLength()
    {
        $this->setExpectedException('SR\Exception\RuntimeException');

        Security::getRandomBytes(0);
    }
}

/* EOF */
