<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Component\DependencyInjection\Exception;

use Scribe\Wonka\Exception\ExceptionInterface;

/**
 * Interface ContainerExceptionInterface.
 */
interface ContainerExceptionInterface extends ExceptionInterface
{
    /**
     * @var string
     */
    const MSG_CONTAINER_GENERAL = 'An unknown container-related error occurred.';

    /**
     * @var string
     */
    const MSG_CONTAINER_INVALID_PARAMETER = 'The requested container parameter "%s" could not be found.';

    /**
     * @var string
     */
    const MSG_CONTAINER_INVALID_SERVICE = 'The requested container service "%s" could not be found.';

    /**
     * @var int
     */
    const CODE_CONTAINER_GENERAL = 5500;
}

/* EOF */
