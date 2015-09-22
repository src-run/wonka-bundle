<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Tests\Utility\Security;

use Scribe\WonkaBundle\Utility\Security\Security;
use Scribe\WonkaBundle\Utility\TestCase\WonkaTestCase;

class SecurityTest extends WonkaTestCase
{
    public function testGenerateRandom()
    {
        $random = Security::generateRandom(100, true);

        static::assertEquals(
            strlen('aXyuvDR2yicN+vk0j0twYA6CeWKq+98qP8jlCjzgZ8sfI0td/8Lqph8c2c2XZpGBHNxbPreqHllDCRXuMqzlAKrhqfOc5LeW0pAAzGgKq4EYz7d5lk050XSoTrw247+8wsFI3g=='),
            strlen($random)
        );
    }

    public function testGenerateRandomLimit()
    {
        $random = Security::generateRandom(100, true, '#[A-Z0-9]#');
        $randomManualRegex = preg_replace('#[A-Z0-9]#', '', $random, -1, $count);

        static::assertEquals($random, $randomManualRegex);
        static::assertEquals(0, $count);
    }

    public function testGenerateRandomHash()
    {
        $hash = Security::generateRandomHash();

        static::assertEquals(
            strlen('4d046472d4374c0f58a14c737d981e06bad93d43fb0ae06e82a9ce93d6bde0b45304034a3640bd526a8dc3e0999830d3a0f69c49a369becb71f87f60b53152bd'),
            strlen($hash)
        );

        $hash = Security::generateRandomHash('md5', false, 10000);

        static::assertEquals(
            strlen('199b3a1440496a989dfe818b73136ece'),
            strlen($hash)
        );
    }

    public function testDoesPasswordMeetRequirements()
    {
        static::assertFalse(Security::doesPasswordMeetRequirements('abc'));
        static::assertTrue(Security::doesPasswordMeetRequirements('abcdEFGH!@#*0124ijklMNOP^%&)5678'));
        static::assertTrue(Security::doesPasswordMeetRequirements('a1', '#.*^(?=.{2,})(?=.*[a-z])(?=.*[0-9]).*$#'));
        static::assertFalse(Security::doesPasswordMeetRequirements('ab', '#.*^(?=.{2,})(?=.*[a-z])(?=.*[0-9]).*$#'));
    }

    public function testGenerateRandomPassword()
    {
        $passwordShort = Security::generateRandomPassword(8);

        static::assertEquals(
            8,
            strlen($passwordShort)
        );

        $passwordLong = Security::generateRandomPassword(1000);

        static::assertEquals(
            1000,
            strlen($passwordLong)
        );

        $this->setExpectedException(
            'Scribe\Wonka\Exception\RuntimeException',
            'Reached loop count trying to create random password in "Scribe\WonkaBundle\Utility\Security\Security::generateRandomPassword". Lower requirements.'
        );

        $passwordImpossible = Security::generateRandomPassword(1);
    }
}

/* EOF */
