<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Component\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Scribe\Wonka\Utility\Arrays;
use Scribe\Wonka\Utility\ClassInfo;
use Scribe\Wonka\Utility\Filter\StringFilter;
use Scribe\Wonka\Exception\RuntimeException;
use Scribe\WonkaBundle\Component\DependencyInjection\Container\ContainerAwareInterface;
use Scribe\WonkaBundle\Component\DependencyInjection\Container\ContainerAwareTrait;
use Scribe\WonkaBundle\Component\DependencyInjection\Loader\XmlFileLoader;
use Scribe\WonkaBundle\Component\DependencyInjection\Loader\YamlFileLoader;
use Scribe\WonkaBundle\Utility\Locator\BundleLocator;

/**
 * Class AbstractDisableableExtension.
 */
abstract class AbstractDisableableExtension extends AbstractExtension
{
    /**
     * Helper for bundles that can be disabled {@see \Symfony/Component/Config/Definition/Builder/ArrayNodeDefinition::canBeDisabled()}
     * before calling parent implementation.
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
