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

namespace Scribe\WonkaBundle\Utility\Config;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Scribe\WonkaBundle\Component\DependencyInjection\Container\ContainerAwareTrait;

/**
 * Class ConfigContainer.
 */
class ConfigContainer implements ConfigInterface
{
    use ContainerAwareTrait;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    /**
     * @param string $parameterId
     *
     * @return null|mixed
     */
    public function get($parameterId)
    {
        if (false === $this->has($parameterId)) {
            return null;
        }

        return $this->container->getParameter($parameterId);
    }

    /**
     * @param string $parameterId
     *
     * @return bool
     */
    public function has($parameterId)
    {
        return (bool) $this->container->hasParameter((string) $parameterId);
    }
}

/* EOF */
