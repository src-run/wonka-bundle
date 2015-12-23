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

use Scribe\WonkaBundle\Component\DependencyInjection\Builder\NodeBuilderManager;
use Scribe\WonkaBundle\Utility\Locator\BundleLocator;
use Scribe\WonkaBundle\Component\DependencyInjection\Builder\TreeBuilderManager;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class AbstractConfiguration.
 */
abstract class AbstractConfiguration implements ConfigurationInterface
{
    /**
     * @var TreeBuilderManager
     */
    protected $builderRoot;

    /**
     * @var TreeBuilderManager[]
     */
    protected $builderSet = [];

    /**
     * @var NodeBuilderManager[]
     */
    protected $nodeBuilderSet = [];

    /**
     * @return NodeDefinition
     */
    public function getConfigTreeBuilder()
    {
        $this->getBuilderRoot()->getNodeDefinition()->end();

        return $this->getBuilderRoot()->getTreeBuilder();
    }

    /**
     * @return string
     */
    protected function getRootName()
    {
        $caller = get_called_class();
        list($v, $b) = BundleLocator::bundlePartsFromNamespace($caller);

        return $v.'_'.$b;
    }

    /**
     * @return TreeBuilderManager
     */
    protected function getBuilderRoot()
    {
        if (false === ($this->builderRoot instanceof TreeBuilderManager)) {
            $name = $this->getRootName();
            $this->builderRoot = TreeBuilderManager::create()->newBuilder($name);
        }

        return $this->builderRoot;
    }

    /**
     * @return TreeBuilder
     */
    protected function getBuilder($name)
    {
        if (false === array_key_exists((string) $name, $this->builderSet)) {
            $this->builderSet[(string) $name] = $this->getGhostBuilder($name);
        }

        return $this->builderSet[(string) $name];
    }

    /**
     * @param  string $name
     * 
     * @return TreeBuilder
     */
    protected function getGhostBuilder($name)
    {
        return TreeBuilderManager::create()->newBuilder((string) $name);
    }

    /**
     * @param $name
     * @param $type
     *
     * @return NodeBuilderManager
     */
    protected function getNodeDefinition($name, $type)
    {
        if (false === array_key_exists((string) $name, $this->nodeBuilderSet)) {
            $this->nodeBuilderSet[(string) $name] = NodeBuilderManager::create()->newBuilder($name, $type);
        }

        return $this->nodeBuilderSet[(string) $name]->getNodeDefinition();
    }
}

/* EOF */
