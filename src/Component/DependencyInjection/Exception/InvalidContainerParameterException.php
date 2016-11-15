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

use SR\Exception\Logic\LogicException;

/**
 * Class InvalidContainerParameterException.
 */
class InvalidContainerParameterException extends LogicException implements ContainerExceptionInterface
{
    /**
     * @return string
     */
    public function getDefaultMessage()
    {
        return self::MSG_CONTAINER_INVALID_PARAMETER;
    }

    /**
     * @return int
     */
    public function getDefaultCode()
    {
        return self::CODE_CONTAINER_GENERAL;
    }
}

/* EOF */
