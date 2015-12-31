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

namespace Scribe\WonkaBundle\Component\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class AbstractDisableableExtension.
 */
abstract class AbstractDisableableExtension extends AbstractExtension
{
    /**
     * Overrides parent {@see AbstractExtension::autoLoadServices()} method with implementation
     * that disables loading if enabled is false in extension configuration file. Can be used with
     * {@see \Symfony/Component/Config/Definition/Builder/ArrayNodeDefinition::canBeDisabled()}).
     *
     * @param ContainerBuilder $container
     * @param array|null       $configSet
     *
     * @return $this
     */
    protected function autoLoadServices(ContainerBuilder $container, array $configSet = null)
    {
        if ($this->isEnabled($configSet) !== false) {
            parent::autoLoadServices($container, $configSet);
        }

        return $this;
    }
}

/* EOF */
