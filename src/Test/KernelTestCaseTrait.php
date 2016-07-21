<?php

/*
 * This file is part of the `src-run/wonka-bundle` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\WonkaBundle\Test;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Trait KernelTestCaseTrait.
 */
trait KernelTestCaseTrait
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected static $container;

    /**
     * @return bool
     */
    protected function hasContainer()
    {
        return static::$container instanceof ContainerInterface;
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected function getContainer()
    {
        return static::$container;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function hasService($name)
    {
        return $this->getContainer()->has($name);
    }

    /**
     * @param string $name
     *
     * @return object
     */
    protected function getService($name)
    {
        if ($this->hasService($name)) {
            return $this->getContainer()->get($name);
        }

        throw new \RuntimeException(sprintf('Could not get service "%s" in test "%s".', $name, static::class));
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function hasParameter($name)
    {
        return $this->getContainer()->hasParameter($name);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    protected function getParameter($name)
    {
        if ($this->hasParameter($name)) {
            return $this->getContainer()->getParameter($name);
        }

        throw new \RuntimeException(sprintf('Could not get parameter "%s" in test "%s".', $name, static::class));
    }
}

/* EOF */
