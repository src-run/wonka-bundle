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

namespace Scribe\WonkaBundle\Component\DependencyInjection\Compiler\Pass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface as BaseCompilerPassInterface;

/**
 * Class CompilerPassInterface.
 */
interface CompilerPassInterface extends BaseCompilerPassInterface
{
    const REGISTRAR_ADD_ATTENDANT_METHOD_NAME = 'addAttendant';

    /**
     * Return the name of the service that handles the collection of tagged items found (the chain manager).
     *
     * @return string
     */
    public function getRegistrarSrvName();

    /**
     * Return the name of the search tag to find services to be attached to the chain (the chain subscribers).
     *
     * @return string
     */
    public function getAttendantTagName();

    /**
     * @return string
     */
    public function getRegistrarAddAttendantMethodName();

    /**
     * @param string $methodName\
     */
    public function setRegistrarAddAttendantMethodName($methodName);
}

/* EOF */
