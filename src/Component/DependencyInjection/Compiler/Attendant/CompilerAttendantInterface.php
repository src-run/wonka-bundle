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

namespace Scribe\WonkaBundle\Component\DependencyInjection\Compiler\Attendant;

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
     * @param mixed,... $by
     *
     * @return bool
     */
    public function isSupported(...$by);

    /**
     * @param false|bool $resolveQualifiedName
     *
     * @return string
     */
    public function getType($resolveQualifiedName = false);
}

/* EOF */
