<?php

/*
 * This file is part of the `src-run/wonka-bundle` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\WonkaBundle\Tests\Component\DependencyInjection\Compiler\Fixtures;

use SR\WonkaBundle\Component\DependencyInjection\Compiler\Attendant\AbstractCompilerAttendant;

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
