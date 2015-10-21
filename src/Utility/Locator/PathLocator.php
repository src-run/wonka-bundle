<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Utility\Locator;

use Symfony\Component\Finder\Finder;

/**
 * OrderedYamlDoctrineFixture.
 */
class PathLocator
{
    /**
     * @var array
     */
    protected $paths = [];

    /**
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * @param array $paths
     */
    public function __construct(array $paths = [])
    {
        $this->addPathCollection($paths);
    }

    /**
     * @param string   $path
     * @param int|null $priority
     *
     * @return $this
     */
    public function addPath($path, $priority = null)
    {
        $this->reStackPaths($path, $priority);

        return $this;
    }

    /**
     * @param array $pathCollection
     *
     * @return $this
     */
    public function addPathCollection(array $pathCollection = [])
    {
        foreach ($pathCollection as $priority => $path) {
            $this->addPath($path, $priority);
        }

        return $this;
    }

    /**
     * @param bool $finder
     *
     * @return array|Finder
     */
    public function getValidPathCollection($finder = true)
    {
        $paths = array_filter($this->paths, function ($path) {
            return false !== realpath($path);
        });

        if (0 === count($paths)) {
            return [];
        }

        return $finder === true ? Finder::create()->in($paths) : array_values($paths);
    }

    /**
     * @param bool $finder
     *
     * @return bool|string|Finder
     */
    public function getFirstValidPath($finder = true)
    {
        if (null === ($first = array_first($this->getValidPathCollection(false)))) {
            return false;
        }

        return $finder === true ? Finder::create()->in($first) : $first;
    }

    /**
     * @param string   $path
     * @param int|null $priority
     *
     * @return $this
     */
    protected function reStackPaths($path, $priority)
    {
        $count = count($this->paths);
        $offset = array_search($priority, array_keys($this->paths), false);

        if (false === is_int($priority) || false === $offset) {
            $this->paths[$count] = $path;

            return $this;
        }

        $offset = $offset - 1 > 0 ? $offset - 1 : $offset;

        $this->paths = array_merge(
            array_slice($this->paths, 0, $offset),
            (array) $path,
            array_slice($this->paths, $offset)
        );

        return $this;
    }
}

/* EOF */
