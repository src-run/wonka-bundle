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

use SR\Primitive\SimpleCollection;

/**
 * Class TwigOptionsDefinition.
 */
class TwigOptionsDefinition extends SimpleCollection
{
    /**
     * Merges the passed options, optionally overwriting duplicate options or
     * keeping existing options.
     *
     * @param TwigOptionsDefinition $options
     * @param bool                  $overwrite
     *
     * @return $this
     */
    public function merge(TwigOptionsDefinition $options, $overwrite = true)
    {
        foreach ($options->toArray() as $name => $option) {
            if (array_key_exists($name, $this->elements) && $overwrite === false) {
                continue;
            }

            $this->set($name, $option);
        }

        return $this;
    }
}

/* EOF */
