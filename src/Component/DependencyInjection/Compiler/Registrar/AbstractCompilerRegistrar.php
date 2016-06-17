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

namespace SR\WonkaBundle\Component\DependencyInjection\Compiler\Registrar;

use SR\Wonka\Utility\Mapper\ParametersToPropertiesMapperTrait;
use SR\WonkaBundle\Component\DependencyInjection\Compiler\Attendant\CompilerAttendantInterface;

/**
 * Class AbstractCompilerRegistrar.
 */
abstract class AbstractCompilerRegistrar implements CompilerRegistrarInterface
{
    use ParametersToPropertiesMapperTrait;

    /**
     * @var CompilerAttendantInterface[]
     */
    protected $attendantCollection = [];

    /**
     * @var string[]
     */
    protected $interfaceCollection = [CompilerAttendantInterface::INTERFACE_NAME];

    /**
     * Construct object with default parameters. Any number of parameters may be passed so long as they are each a
     * single-element associative array of the form [propertyName=>propertyValue]. If passed, these additional
     * parameters will overwrite the default instance properties and, as such, runtime handling of this registrar.
     *
     * @param array[] ...$parameters
     */
    public function __construct(...$parameters)
    {
        $this->assignPropertyCollectionToSelf(...$parameters);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->attendantCollection);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->attendantCollection);
    }

    /**
     * @return CompilerAttendantInterface[]
     */
    public function getAttendantCollection()
    {
        return $this->attendantCollection;
    }

    /**
     * @param CompilerAttendantInterface $attendant
     * @param null|int                   $priority
     * @param array                      $extra
     *
     * @return $this
     */
    public function addAttendant(CompilerAttendantInterface $attendant, $priority = null, $extra = [])
    {
        if ($this->isValidAttendant($attendant)) {
            $this->attendantCollection[ $this->getNextAttendantPriority($priority) ] = $attendant;
            ksort($this->attendantCollection);
        }

        return $this;
    }

    /**
     * @param CompilerAttendantInterface $attendant
     *
     * @return bool
     */
    public function hasAttendant(CompilerAttendantInterface $attendant)
    {
        return in_array($attendant, $this->attendantCollection, true);
    }

    /**
     * @param CompilerAttendantInterface $attendant
     *
     * @return bool
     */
    protected function isValidAttendant(CompilerAttendantInterface $attendant)
    {
        foreach ($this->interfaceCollection as $interface) {
            if (!$attendant instanceof $interface) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param null|int $priority
     *
     * @return int
     */
    protected function getNextAttendantPriority($priority = null)
    {
        return is_int($priority) ? $priority : count($this->attendantCollection) - 1;
    }
}

/* EOF */
