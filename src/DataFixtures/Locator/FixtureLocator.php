<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\DataFixtures\Locator;
use Scribe\Wonka\Exception\RuntimeException;

/**
 * DataFixturesLocator.
 */
class FixtureLocator implements FixtureLocatorInterface
{
    /**
     * @var int
     */
    protected $depth;

    /**
     * @var string[]
     */
    protected $paths;

    /**
     * @var null|string
     */
    protected $base;

    /**
     * @param array $paths
     * @param int   $depth
     */
    public function __construct(array $paths = [], $depth = 1)
    {
        $this->paths = $paths;
        $this->depth = $depth;
    }

    /**
     * @param int $depth
     *
     * @return $this
     */
    public function setDepth($depth)
    {
        $this->depth = (int) $depth;

        return $this;
    }

    /**
     * @param string[] $searchPaths
     *
     * @return $this
     */
    public function setPaths($searchPaths)
    {
        $this->paths = $searchPaths;

        return $this;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setBase($path)
    {
        $this->base = $path;

        return $this;
    }

    /**
     * @param string    $name
     * @param true|bool $first
     *
     * @return array
     */
    public function locate($name, $first = true)
    {
        $paths = $this->locateValidPaths($this->paths, $this->base, $name);

        return (array) ($first === true ? array_first($paths) : $paths);
    }

    /**
     * @param string[]    $paths
     * @param null|string $base
     * @param null|string $file
     *
     * @return string[]
     */
    public function locateValidPaths(array $paths, $base = null, $file = null)
    {
        $paths = array_filter($paths, function(&$path) use ($base, $file) {
            $path = realpath($base . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . ($file ?: ''));
            return (bool) ($path ?: false);
        });

        return (array) $paths;
    }
}

/* EOF */
