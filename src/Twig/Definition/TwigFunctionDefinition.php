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

/**
 * Class TwigFunctionDefinition.
 */
final class TwigFunctionDefinition extends AbstractTwigDefinition
{
    /**
     * @return \Twig_Function
     */
    public function getNativeInstance()
    {
        return new \Twig_Function($this->name, $this->callable, $this->options->toArray());
    }
}

/* EOF */
