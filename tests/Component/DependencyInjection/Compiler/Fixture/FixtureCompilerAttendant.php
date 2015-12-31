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

namespace Scribe\WonkaBundle\Tests\Component\DependencyInjection\Compiler\Fixture;

use Scribe\WonkaBundle\Component\DependencyInjection\Compiler\Attendant\AbstractCompilerAttendant;

/**
 * Class CompilerAttendant.
 */
class FixtureCompilerAttendant extends AbstractCompilerAttendant
{
    /**
     * @param mixed,... $by
     *
     * @return bool
     */
    public function isSupported(...$by)
    {
        return true;
    }
}

/* EOF */
