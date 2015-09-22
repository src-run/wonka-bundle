<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\DependencyInjection\Compiler;

/**
 * Class AbstractCompilerPass.
 */
interface CompilerPassInterface
{
    const CHAIN_ADD_METHOD_NAME = 'addHandler';
}

/* EOF */
