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

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Scribe\WonkaBundle\Utility\Locator\BundleLocator;
use Scribe\WonkaBundle\Component\DependencyInjection\Builder\Builder;

/**
 * Class AbstractConfiguration.
 */
abstract class AbstractConfiguration implements ConfigurationInterface
{
    /**
     * @var Builder
     */
    protected $builderRoot;

    /**
     * @var Builder[]
     */
    protected $builderSet = [];

    /**
     * @return NodeDefinition
     */
    public function getConfigTreeBuilder()
    {
        $this->getBuilderRoot()->getNodeBuilder()->end();

        return $this->getBuilderRoot()->getTreeBuilder();
    }

    /**
     * @return string
     */
    protected function getRootName()
    {
        $caller      = get_called_class();
        list($v, $b) = BundleLocator::bundlePartsFromNamespace($caller);

        return $v.'_'.$b;
    }

    /**
     * @return Builder
     */
    protected function getBuilderRoot()
    {
        if (false === ($this->builderRoot instanceof Builder)) {
            $name = $this->getRootName();
            $this->builderRoot = Builder::create()->setupBuilder($name);
        }

        return $this->builderRoot;
    }

    /**
     * @return Builder
     */
    protected function getBuilder($name)
    {
        if (false === array_key_exists((string) $name, $this->builderSet)) {
            $this->builderSet[(string) $name] = Builder::create()->setupBuilder((string) $name);
        }

        return $this->builderSet[(string) $name];
    }
}

/* EOF */
