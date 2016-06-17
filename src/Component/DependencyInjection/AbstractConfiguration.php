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

use SR\WonkaBundle\Component\DependencyInjection\Builder\NodeBuilderManager;
use SR\WonkaBundle\Component\HttpKernel\Bundle\BundleInspect;
use SR\WonkaBundle\Component\DependencyInjection\Builder\TreeBuilderManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration.
 */
class AbstractConfiguration implements ConfigurationInterface
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
        return BundleInspect::getName(get_called_class());
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
     * @return TreeBuilderManager
     */
    protected function getBuilder($name)
    {
        if (false === array_key_exists((string) $name, $this->builderSet)) {
            $this->builderSet[(string) $name] = $this->getGhostBuilder($name);
        }

        return $this->builderSet[(string) $name];
    }

    /**
     * @param string $name
     *
     * @return TreeBuilderManager
     */
    protected function getGhostBuilder($name)
    {
        return TreeBuilderManager::create()->newBuilder((string) $name);
    }

    /**
     * @param $name
     * @param $type
     *
     * @return NodeDefinition|ArrayNodeDefinition
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
