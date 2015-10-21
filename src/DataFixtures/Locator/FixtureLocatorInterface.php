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

/**
 * FixtureLocatorInterface.
 */
interface FixtureLocatorInterface
{
    /**
     * @param int $depth
     *
     * @return $this
     */
    public function setDepth($depth);

    /**
     * @param string[] $searchPaths
     *
     * @return $this
     */
    public function setPaths($searchPaths);

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setBase($path);

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function locate($name);
}

/* EOF */
