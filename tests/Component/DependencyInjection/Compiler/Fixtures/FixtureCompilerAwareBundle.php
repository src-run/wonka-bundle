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

namespace SR\WonkaBundle\Tests\Component\DependencyInjection\Compiler\Fixtures;

use SR\WonkaBundle\Component\DependencyInjection\Compiler\Pass\AbstractCompilerPass;

/**
 * Class FixtureCompilerAwareBundle.
 */
class FixtureCompilerAwareBundle extends AbstractCompilerPass
{
    /**
     * Return the name of the service that handles the collection of tagged items found (the chain manager).
     *
     * @return string
     */
    public function getRegistrarService()
    {
        return 'srv.name';
    }

    /**
     * Return the name of the search tag to find services to be attached to the chain (the chain subscribers).
     *
     * @return string
     */
    public function getAttendantTagName()
    {
        return 'tag.name';
    }
}

/* EOF */
