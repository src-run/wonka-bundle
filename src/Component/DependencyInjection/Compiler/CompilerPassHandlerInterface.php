<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Component\DependencyInjection\Compiler;

/**
 * Class CompilerPassHandlerInterface.
 */
interface CompilerPassHandlerInterface
{
    /**
     * The default restriction to check against when adding handler.
     *
     * @var string
     */
    const INTERFACE_NAME = __CLASS__;

    /**
     * Allows the chain to determine if the handler is supported.
     *
     * @param string ...$by
     *
     * @return bool
     */
    public function isSupported(...$by);

    /**
     * Get the handler type (generally this will return the class name).
     *
     * @param bool $fqcn
     *
     * @return string
     */
    public function getType($fqcn = false);
}

/* EOF */
