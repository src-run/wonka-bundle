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

use Scribe\Wonka\Exception\LogicException;

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
