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

namespace SR\WonkaBundle\Component\DependencyInjection\Compiler\Attendant;

/**
 * Class CompilerAttendantInterface.
 */
interface CompilerAttendantInterface
{
    /**
     * @var string
     */
    const INTERFACE_NAME = __CLASS__;

    /**
     * @param mixed ...$by
     *
     * @return bool
     */
    public function isSupported(...$by);

    /**
     * @param bool $qualified
     *
     * @return string
     */
    public function getType($qualified = false);
}

/* EOF */
