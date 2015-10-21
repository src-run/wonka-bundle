<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\DataFixtures\Loader;

use Symfony\Component\Config\Loader\LoaderResolver;

/**
 * FixtureLoaderResolver.
 */
class FixtureLoaderResolver extends LoaderResolver implements FixtureLoaderResolverInterface
{
    /**
     * @param \Scribe\WonkaBundle\DataFixtures\Loader\FixtureLoaderInterface[] $loaders
     *
     * @return $this
     */
    public function assignLoaders(array $loaders = [])
    {
        foreach ($loaders as $l) {
            $this->addLoader($l);
        }

        return $this;
    }
}

/* EOF */
