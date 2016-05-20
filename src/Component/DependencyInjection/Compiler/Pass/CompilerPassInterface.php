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

namespace SR\WonkaBundle\Component\DependencyInjection\Compiler\Pass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface as BaseCompilerPassInterface;

/**
 * Class CompilerPassInterface.
 */
interface CompilerPassInterface extends BaseCompilerPassInterface
{
    /**
     * Return the name of the service that handles the collection of tagged items found (the chain manager).
     *
     * @return string
     */
    public function getRegistrarService();

    /**
     * Return the name of the search tag to find services to be attached to the chain (the chain subscribers).
     *
     * @return string
     */
    public function getAttendantTagName();

    /**
     * @return string
     */
    public function getRegistrarAddAttendantMethodName();
}

/* EOF */
