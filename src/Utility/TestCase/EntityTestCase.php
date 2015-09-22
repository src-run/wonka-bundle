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

use Doctrine\ORM\EntityManager;

/**
 * Class EntityTestCase.
 *
 * Expands on our Kernel test case to provide helper functions for Doctrine's entity manager.
 */
abstract class EntityTestCase extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * handle constructing the object instance.
     */
    public function setUp()
    {
        parent::setUp();

        $this->setupEM();
    }

    /**
     * @return $this
     */
    private function setupEM()
    {
        $this->em = $this
            ->container
            ->get('doctrine')
            ->getManager()
        ;

        if (false === ($this->em instanceof EntityManager)) {
            throw new \RuntimeException('Unable to obtain a valid Doctrine EntityManager instance.');
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        $this
            ->em
            ->close()
        ;

        parent::tearDown();
    }
}
