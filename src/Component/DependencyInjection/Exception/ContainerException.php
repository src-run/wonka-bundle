<?php

/*
 * This file is part of the Wonka Bundle.
 *
 * (c) Scribe Inc.     <scr@src.run>
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Component\DependencyInjection\Exception;

use Scribe\Wonka\Exception\Exception;

/**
 * Class ContainerException.
 */
class ContainerException extends Exception implements ContainerExceptionInterface
{
    /**
     * @return string
     */
    public function getDefaultMessage()
    {
        return self::MSG_CONTAINER_GENERAL;
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
