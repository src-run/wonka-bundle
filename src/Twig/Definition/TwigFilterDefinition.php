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
 * Class TwigFilterDefinition.
 */
final class TwigFilterDefinition extends AbstractTwigDefinition
{
    /**
     * @return \Twig_Filter|\Twig_SimpleFilter
     */
    public function getNativeInstance()
    {
        if (Inspect::useClass('\Twig_Filter')->isAbstract()) {
            return new \Twig_SimpleFilter($this->name, $this->callable, $this->options->toArray());
        }

        return new \Twig_Filter($this->name, $this->callable, $this->options->toArray());
    }
}

/* EOF */
