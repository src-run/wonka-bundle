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
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * Class NodeBuilderManager.
 */
class NodeBuilderManager extends AbstractBuilderManager
{
    /**
     * @var NodeBuilder
     */
    protected $nodeBuilder;

    /**
     * @var NodeDefinition|ArrayNodeDefinition
     */
    protected $nodeDefinition;

    /**
     * @internal
     *
     * @param NodeBuilder $nodeBuilder
     *
     * @return $this
     */
    public function setNodeBuilder(NodeBuilder $nodeBuilder)
    {
        $this->nodeBuilder = $nodeBuilder;
    }

    /**
     * @api
     *
     * @return NodeBuilder
     */
    public function getNodeBuilder()
    {
        return $this->nodeBuilder;
    }

    /**
     * @api
     *
     * @param string $name
     * @param string $type
     *
     * @return $this
     */
    public function newBuilder($name, $type)
    {
        $this->setNodeBuilder(new NodeBuilder());

        $method = $type.'Node';

        if (!method_exists($this->nodeBuilder, $method)) {
            throw new RuntimeException('Invalid node type provided;');
        }

        $this->setNodeDefinition(call_user_func([$this->nodeBuilder, $method], $name));

        return $this;
    }
}

/* EOF */
