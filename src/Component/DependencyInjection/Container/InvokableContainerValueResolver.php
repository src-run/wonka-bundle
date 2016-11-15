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

use SR\WonkaBundle\Component\DependencyInjection\Exception\ContainerException;
use SR\WonkaBundle\Component\DependencyInjection\Exception\InvalidContainerParameterException;
use SR\WonkaBundle\Component\DependencyInjection\Exception\InvalidContainerServiceException;

/**
 * Class InvokableContainerValueResolver.
 */
class InvokableContainerValueResolver implements InvokableContainerValueResolverInterface
{
    use ContainerAwareTrait {
        getContainer as protected;
        hasContainer as protected;
        getContainerParameter as protected;
        hasContainerParameter as protected;
        getContainerService as protected;
        hasContainerService as protected;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $containerLookup
     *
     * @throws ContainerException
     *
     * @return mixed
     */
    public function __invoke($containerLookup)
    {
        if (!$this->hasContainer()) {
            throw ContainerException::create()->setMessage('Container not available as expected.');
        }

        if (substr($containerLookup, 0, 1) === '%') {
            return $this->normalizeAndGetParameter($containerLookup);
        }

        return $this->normalizeAndGetService($containerLookup);
    }

    /**
     * @param string $parameter
     *
     * @throws InvalidContainerParameterException
     *
     * @return mixed
     */
    private function normalizeAndGetParameter($parameter)
    {
        $parameter = preg_replace('/(?:^[%])|(?:[%]$)/', '', $parameter);

        return $this->getContainerParameter($parameter);
    }

    /**
     * @param string $service
     *
     * @throws InvalidContainerServiceException
     *
     * @return mixed
     */
    private function normalizeAndGetService($service)
    {
        $service = preg_replace('/(?:^[@])/', '', $service);

        return $this->getContainerService($service);
    }
}

/* EOF */
