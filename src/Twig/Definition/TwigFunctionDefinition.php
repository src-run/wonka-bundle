<?php

/*
 * This file is part of the `src-run/wonka-bundle` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\WonkaBundle\Twig\Definition;

use SR\Reflection\Inspect;

/**
 * Class TwigFunctionDefinition.
 */
final class TwigFunctionDefinition extends AbstractTwigDefinition
{
    /**
     * @return \Twig_Function|\Twig_SimpleFunction
     */
    public function getNativeInstance()
    {
        if (Inspect::useClass('\Twig_Function')->isAbstract()) {
            return new \Twig_SimpleFunction($this->name, $this->callable, $this->options->toArray());
        }

        return new \Twig_Function($this->name, $this->callable, $this->options->toArray());
    }
}

/* EOF */
