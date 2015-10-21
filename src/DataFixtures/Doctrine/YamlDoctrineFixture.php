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

use Scribe\Wonka\Exception\RuntimeException;
use Scribe\WonkaBundle\DataFixtures\Loader\FixtureLoaderInterface;
use Scribe\WonkaBundle\DataFixtures\Loader\YamlFixtureLoader;

/**
 * YamlDoctrineFixture.
 */
class YamlDoctrineFixture extends AbstractDoctrineFixture
{
    /**
     * @return string
     */
    public function getType()
    {
        return FixtureLoaderInterface::RESOURCE_YAML;
    }
}

/* EOF */
