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

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * Class AbstractBuilderManager.
 */
abstract class AbstractBuilderManager
{
    /**
     * @var NodeDefinition|ArrayNodeDefinition
     */
    protected $nodeDefinition;

    /**
     * @api
     *
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param NodeDefinition $nodeDefinition
     *
     * @return $this
     */
    public function setNodeDefinition(NodeDefinition $nodeDefinition)
    {
        $this->nodeDefinition = $nodeDefinition;
    }

    /**
     * @api
     *
     * @return NodeDefinition|ArrayNodeDefinition
     */
    public function getNodeDefinition()
    {
        return $this->nodeDefinition;
    }
}

/* EOF */
