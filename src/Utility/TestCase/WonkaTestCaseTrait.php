<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Utility\TestCase;

/**
 * Class WonkaTestCaseTrait.
 */
trait WonkaTestCaseTrait
{
    /**
     * @param $dirPath
     */
    protected function removeDirectoryIfExists($dirPath)
    {
        if (false === is_dir($dirPath)) {
            return;
        }

        $this->removeDirectory($dirPath);
    }

    /**
     * Remove a directory.
     *
     * @param string $path
     */
    protected function removeDirectory($path)
    {
        $files = glob($path.'/*');

        if (false === is_array($files)) {
            return;
        }

        foreach ($files as $file) {
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
        }

        rmdir($path);
    }

    /**
     * Alias for {@see:removeDirectory}.
     *
     * @deprecated
     *
     * @param string $path
     */
    protected function removeDirectoryRecursive($path)
    {
        $this->removeDirectory($path);
    }
}

/* EOF */
