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

namespace SR\WonkaBundle\Component\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class DisableableExtension.
 */
class DisableableExtension extends Extension
{
    /**
     * Overrides parent {@see AbstractExtension::autoLoadServices()} method with implementation
     * that disables loading if enabled is false in extension configuration file. Can be used with
     * {@see \Symfony/Component/Config/Definition/Builder/ArrayNodeDefinition::canBeDisabled()}).
     *
     * @param ContainerBuilder $container
     * @param array|null       $config
     *
     * @return $this
     */
    protected function autoLoadServices(ContainerBuilder $container, array $config = null)
    {
        if ($this->isEnabled($config) !== false) {
            parent::autoLoadServices($container, $config);
        }

        return $this;
    }
}

/* EOF */
