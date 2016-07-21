<?php

/*
 * This file is part of the `src-run/wonka-bundle` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\WonkaBundle\Utility\Path;

/**
 * Class Path.
 */
class Path
{
    /**
     * @param string $directoryPath
     */
    public static function remove($path, $removeRoot = false)
    {
        $path = realpath($path);

        if ($path !== false && is_dir($path)) {
            static::removeRecursive($path, !$removeRoot);
        }
    }

    /**
     * @param string $path
     */
    private static function removeRecursive($basePath, $root = false)
    {
        foreach (scandir($basePath, SCANDIR_SORT_NONE) as $name) {
            if ($name === '.' || $name === '..') {
                continue;
            }

            $path = $basePath.DIRECTORY_SEPARATOR.$name;

            is_dir($path) ? static::removeRecursive($path, false) : unlink($path);
        }

        if ($root === false) {
            rmdir($basePath);
        }
    }
}

/* EOF */
