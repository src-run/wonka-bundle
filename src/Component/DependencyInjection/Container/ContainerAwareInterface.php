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

namespace Scribe\WonkaBundle\Component\DependencyInjection\Container;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface as SymfonyContainerAwareInterface;

/**
 * Class ContainerAwareInterface.
 */
interface ContainerAwareInterface extends SymfonyContainerAwareInterface
{
    /**
     * Getter for container property.
     *
     * @return ContainerInterface
     */
    public function getContainer();

    /**
     * Checker for container property.
     *
     * @return bool
     */
    public function hasContainer();

    /**
     * Get a parameter from the container.
     *
     * @param string $parameter
     *
     * @return mixed
     */
    public function getContainerParameter($parameter);

    /**
     * Checks if the container parameter exists.
     *
     * @param string $parameter
     *
     * @return bool
     */
    public function hasContainerParameter($parameter);

    /**
     * Get a service from the container.
     *
     * @param string $service
     *
     * @return mixed
     */
    public function getContainerService($service);

    /**
     * Checks if the container service exists.
     *
     * @param string $service
     *
     * @return bool
     */
    public function hasContainerService($service);
}

/* EOF */
