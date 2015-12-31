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

namespace Scribe\WonkaBundle\Component\DependencyInjection\Compiler\Registrar;

use Scribe\WonkaBundle\Component\DependencyInjection\Compiler\Attendant\CompilerAttendantInterface;

/**
 * Class CompilerRegistrarInterface.
 */
interface CompilerRegistrarInterface extends \IteratorAggregate, \Countable
{
    /**
     * @return int
     */
    public function count();

    /**
     * @return \ArrayIterator
     */
    public function getIterator();

    /**
     * @return CompilerAttendantInterface[]
     */
    public function getAttendantCollection();

    /**
     * @param CompilerAttendantInterface $attendant
     * @param null|int                   $priority
     * @param array                      $extra
     *
     * @return $this
     */
    public function addAttendant(CompilerAttendantInterface $attendant, $priority = null, $extra = []);

    /**
     * @param CompilerAttendantInterface $attendant
     *
     * @return bool
     */
    public function hasAttendant(CompilerAttendantInterface $attendant);
}

/* EOF */
