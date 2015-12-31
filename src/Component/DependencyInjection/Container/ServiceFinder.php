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

use Scribe\Wonka\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ServiceFinder.
 */
class ServiceFinder
{
    /**
     * @var ContainerInterface
     */
    private $container = null;

    /**
     * Setup the object instance.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Any call to the object tries to fetch
     * the given string argument as a service.
     *
     * @param string
     *
     * @throws RuntimeException If the service cannot be found.
     *
     * @return object
     */
    public function __invoke($service)
    {
        if ($this->container->has($service)) {
            return $this->container->get($service);
        }

        throw new RuntimeException('Service %s not found by %s finder service.', null, null, $service, get_called_class());
    }
}

/* EOF */
