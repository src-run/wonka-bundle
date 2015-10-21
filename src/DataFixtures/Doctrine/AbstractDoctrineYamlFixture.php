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

use Scribe\Wonka\Utility\Error\DeprecationErrorHandler;

/**
 * AbstractDoctrineYamlFixture.
 *
 * @deprecated
 */
abstract class AbstractDoctrineYamlFixture extends OrderedYamlDoctrineFixture
{
    /**
     * @deprecated
     */
    public function __construct()
    {
        DeprecationErrorHandler::trigger(
            __METHOD__, __LINE__,
            'Use \Scribe\WonkaBundle\DataFixtures\DoctrineDoctrineFixture.',
            '2015-10-19 15:00 -0500', '2015-12-01'
        );
    }
}

/* EOF */
