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

namespace SR\WonkaBundle\Component\DependencyInjection\Builder;

use SR\Exception\Runtime\RuntimeException;
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

        return $this;
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
            throw RuntimeException::create()->setMessage('Builder name must be provided when creating tree builders.');
        }

        $this->setTreeBuilder(new TreeBuilder());
        $this->setNodeDefinition($this->treeBuilder->root($name));

        return $this;
    }
}

/* EOF */
