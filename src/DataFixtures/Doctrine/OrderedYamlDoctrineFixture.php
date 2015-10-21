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

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * OrderedYamlDoctrineFixture.
 */
class OrderedYamlDoctrineFixture extends YamlDoctrineFixture implements OrderedFixtureInterface
{
    /**
     * @return int
     */
    public function getOrder()
    {
        return (int) $this->m->getPriority();
    }
}

/* EOF */
