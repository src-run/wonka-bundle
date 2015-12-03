<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Component\DependencyInjection\Builder;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * Class Builder.
 */
class Builder
{
    /**
     * @var TreeBuilder
     */
    protected $treeBuilder;

    /**
     * @var NodeDefinition
     */
    protected $nodeBuilder;

    /**
     * @return Builder
     */
    public static function create()
    {
        return new self();
    }

    /**
     * @return TreeBuilder
     */
    public function getTreeBuilder()
    {
        return $this->treeBuilder;
    }

    /**
     * @param TreeBuilder $treeBuilder
     *
     * @return $this
     */
    public function setTreeBuilder(TreeBuilder $treeBuilder)
    {
        $this->treeBuilder = $treeBuilder;
    }

    /**
     * @return NodeDefinition|ArrayNodeDefinition
     */
    public function getNodeBuilder()
    {
        return $this->nodeBuilder;
    }

    /**
     * @param NodeDefinition $nodeBuilder
     *
     * @return $this
     */
    public function setNodeBuilder(NodeDefinition $nodeBuilder)
    {
        $this->nodeBuilder = $nodeBuilder;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setupBuilder($name)
    {
        $this->setupBuilderEmpty();
        $this->setNodeBuilder($this->treeBuilder->root($name));

        return $this;
    }

    /**
     * @return $this
     */
    public function setupBuilderEmpty()
    {
        $this->setTreeBuilder($treeBuilder = new TreeBuilder());

        return $this;
    }
}

/* EOF */
