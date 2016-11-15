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

        return $this;
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
            throw RuntimeException::create()
                ->setMessage('Invalid node type "%s" provided when asking for new builder.', $method);
        }

        $this->setNodeDefinition(call_user_func([$this->nodeBuilder, $method], $name));

        return $this;
    }
}

/* EOF */
