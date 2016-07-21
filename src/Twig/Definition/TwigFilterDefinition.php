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
 * Class TwigFilterDefinition.
 */
final class TwigFilterDefinition extends AbstractTwigDefinition
{
    /**
     * @return \Twig_Filter
     */
    public function getNativeInstance()
    {
        return new \Twig_Filter($this->name, $this->callable, $this->options->toArray());
    }
}

/* EOF */
