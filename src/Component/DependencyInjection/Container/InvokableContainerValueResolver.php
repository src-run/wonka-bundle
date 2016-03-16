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
use Scribe\WonkaBundle\Component\DependencyInjection\Exception\ContainerException;
use Scribe\WonkaBundle\Component\DependencyInjection\Exception\InvalidContainerParameterException;
use Scribe\WonkaBundle\Component\DependencyInjection\Exception\InvalidContainerServiceException;

/**
 * Class InvokableContainerValueResolver.
 */
class InvokableContainerValueResolver implements InvokableContainerValueResolverInterface
{
    use ContainerAwareTrait {
        getContainer as protected,
        hasContainer as protected,
        getContainerParameter as protected,
        hasContainerParameter as protected,
        getContainerService as protected,
        hasContainerService as protected;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $lookup
     *
     * @throws ContainerException
     *
     * @return mixed
     */
    public function __invoke($lookup)
    {
        if (!$this->hasContainer()) {
            throw ContainerException::create()
                ->setMessage('Required container not injected into "%s".')
                ->with(get_called_class());
        }

        if (substr($lookup, 0, 1) === '%') {
            return $this->normalizeAndGetParameter($lookup);
        }

        return $this->normalizeAndGetService($lookup);
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

        if ($this->hasContainerParameter($parameter)) {
            return $this->getContainerParameter($parameter);
        }

        throw InvalidContainerParameterException::create()
            ->setMessage('Parameter "%s" could not be found in "%s".')
            ->with($parameter, get_called_class());
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

        if ($this->hasContainerService($service)) {
            return $this->getContainerService($service);
        }

        throw InvalidContainerServiceException::create()
            ->setMessage('Service "%s" could not be found in "%s".')
            ->with($service, get_called_class());
    }
}

/* EOF */
