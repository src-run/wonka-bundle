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
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class CompilerAwareBundle.
 */
class CompilerAwareBundle extends Bundle implements CompilerAwareBundleInterface
{
    /**
     * @var CompilerPassInterface[]
     */
    private $compilerPasses = [];

    /**
     * @param CompilerPassInterface $compilerPass
     *
     * @return $this
     */
    final public function registerCompilerPass(CompilerPassInterface $compilerPass)
    {
        $this->compilerPasses[] = $compilerPass;

        return $this;
    }

    /**
     * @param ContainerBuilder $builder
     *
     * @return ContainerBuilder
     */
    final protected function buildCompilerPasses(ContainerBuilder $builder)
    {
        foreach ($this->compilerPasses as $compilerPass) {
            $builder->addCompilerPass($compilerPass);
        }

        return $builder;
    }

    /**
     * @param ContainerBuilder $builder
     */
    public function build(ContainerBuilder $builder)
    {
        parent::build($builder);

        $this->buildCompilerPasses($builder);
    }
}

/* EOF */
