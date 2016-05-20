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

namespace SR\WonkaBundle\Component\HttpKernel\Bundle;

use SR\WonkaBundle\Component\DependencyInjection\Compiler\Pass\CompilerPassInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Class CompilerAwareBundleInterface.
 */
interface CompilerAwareBundleInterface extends BundleInterface
{
    /**
     * @param CompilerPassInterface $compilerPass
     *
     * @return $this
     */
    public function registerCompilerPass(CompilerPassInterface $compilerPass);
}

/* EOF */
