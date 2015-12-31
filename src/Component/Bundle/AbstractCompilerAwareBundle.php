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

namespace Scribe\WonkaBundle\Component\Bundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Scribe\WonkaBundle\Component\DependencyInjection\Compiler\Pass\CompilerPassInterface;

/**
 * Class AbstractCompilerAwareBundle.
 */
abstract class AbstractCompilerAwareBundle extends Bundle implements CompilerAwareBundleInterface
{
    /**
     * @return CompilerPassInterface[]
     */
    abstract public function getCompilerPassInstances();

    /**
     * @param ContainerBuilder $builder
     */
    public function build(ContainerBuilder $builder)
    {
        parent::build($builder);

        foreach ($this->getCompilerPassInstances() as $pass) {
            if ($pass instanceof CompilerPassInterface) {
                $builder->addCompilerPass($pass);
            }
        }
    }
}

/* EOF */
