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

use Scribe\Wonka\Exception\RuntimeException;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class TreeBuilderManager.
 */
class TreeBuilderManager extends AbstractBuilderManager
{
    /**
     * @var TreeBuilder
     */
    protected $treeBuilder;

    /**
     * @internal
     *
     * @param TreeBuilder $treeBuilder
     *
     * @return $this
     */
    public function setTreeBuilder(TreeBuilder $treeBuilder)
    {
        $this->treeBuilder = $treeBuilder;
    }

    /**
     * @api
     *
     * @return TreeBuilder
     */
    public function getTreeBuilder()
    {
        return $this->treeBuilder;
    }

    /**
     * @api
     *
     * @param string $name
     *
     * @return $this
     */
    public function newBuilder($name = null)
    {
        if ($name === null) {
            throw new RuntimeException('Builder name must be provided when creating tree builders.');
        }

        $this->setTreeBuilder(new TreeBuilder());
        $this->setNodeDefinition($this->treeBuilder->root($name));

        return $this;
    }
}

/* EOF */
