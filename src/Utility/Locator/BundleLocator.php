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

namespace Scribe\WonkaBundle\Utility\Locator;

use Scribe\Wonka\Utility\StaticClass\StaticClassTrait;

/**
 * Class BundleLocator.
 */
class BundleLocator
{
    use StaticClassTrait;

    /**
     * @param string $namespace
     *
     * @return array[]
     */
    public static function bundlePartsFromNamespace($namespace)
    {
        preg_match('#([^\\\]*)(?:(?:\\\[^\\\]*)*?)?\\\([^\\\]*)Bundle\\\#', (string) $namespace, $matches);

        if (isEmptyIterable($matches) || count($matches) !== 3) {
            return [null, null];
        }

        return [
            strtolower(preg_replace('#(?<=\\w)(?=[A-Z])#', '_$1', $matches[1])),
            strtolower(preg_replace('#(?<=\\w)(?=[A-Z])#', '_$1', $matches[2])),
        ];
    }
}

/* EOF */
