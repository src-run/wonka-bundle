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
        $normalize = function ($v) {
            if (substr($v, -6) === 'Bundle') {
                $v = substr($v, 0, strlen($v) - 6);
            }

            return strtolower(preg_replace('#(?<=\\w)(?=[A-Z])#', '_$1', $v));
        };

        $fqcnParts = explode('\\', $namespace);

        if (isEmptyIterable($fqcnParts) || !(count($fqcnParts) >= 2)) {
            return [null, null];
        }

        $bundleRoot = $normalize(array_shift($fqcnParts));

        $bundlePart = array_filter($fqcnParts, function ($v) {
            static $end = false;

            if (substr($v, -6) === 'Bundle') {
                $end = true;

                return true;
            }

            return !$end;
        });

        $bundlePart = array_map($normalize, $bundlePart);

        return [$bundleRoot, implode('_', $bundlePart)];
    }
}

/* EOF */
