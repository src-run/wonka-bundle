<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
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
