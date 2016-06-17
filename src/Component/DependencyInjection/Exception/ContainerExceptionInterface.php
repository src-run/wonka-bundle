<?php

/*
 * This file is part of the `src-run/wonka-bundle` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 * (c) Scribe Inc      <scr@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\WonkaBundle\Component\DependencyInjection\Exception;

use SR\Exception\ExceptionInterface;

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
