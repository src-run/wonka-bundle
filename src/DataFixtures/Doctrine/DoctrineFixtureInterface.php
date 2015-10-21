<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\DataFixtures\Doctrine;

use Scribe\WonkaBundle\Component\DependencyInjection\Container\ContainerAwareInterface;

/**
 * Interface DoctrineFixtureInterface.
 */
interface DoctrineFixtureInterface extends ContainerAwareInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return string[]
     */
    public function getPaths();

    /**
     * @return \Scribe\WonkaBundle\DataFixtures\Loader\FixtureLoaderInterface[]
     */
    public function getLoaders();

    /**
     * @return bool
     */
    public function isLazy();
}

/* EOF */
