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

namespace SR\WonkaBundle\Component\DependencyInjection\Container;

use SR\WonkaBundle\Component\DependencyInjection\Exception\InvalidContainerParameterException;
use SR\WonkaBundle\Component\DependencyInjection\Exception\InvalidContainerServiceException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ContainerAwareTrait.
 */
trait ContainerAwareTrait
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Getter for container property.
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Checker for container property.
     *
     * @return bool
     */
    public function hasContainer()
    {
        return $this->container instanceof ContainerInterface;
    }

    /**
     * Get a parameter from the container.
     *
     * @param string $parameter
     *
     * @throws InvalidContainerParameterException
     *
     * @return mixed
     */
    public function getContainerParameter($parameter)
    {
        if ($this->hasContainerParameter($parameter)) {
            return $this->container->getParameter($parameter);
        }

        throw InvalidContainerParameterException::create()
            ->setMessage('The container parameter requested (%s) does not exist.', $parameter);
    }

    /**
     * Checks if the container parameter exists.
     *
     * @param string $parameter
     *
     * @return bool
     */
    public function hasContainerParameter($parameter)
    {
        return $this->container->hasParameter($parameter);
    }

    /**
     * Get a service from the container.
     *
     * @param string $service
     *
     * @throws InvalidContainerServiceException
     *
     * @return mixed
     */
    public function getContainerService($service)
    {
        if ($this->hasContainerService($service)) {
            return $this->container->get($service);
        }

        throw InvalidContainerParameterException::create()
            ->setMessage('The container service requested (%s) does not exist.', $service);
    }

    /**
     * Checks if the container service exists.
     *
     * @param string $service
     *
     * @return bool
     */
    public function hasContainerService($service)
    {
        return $this->container->has($service);
    }
}

/* EOF */
