<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\DataFixtures\Tree;

/**
 * TreeStore.
 */
class TreeStore implements TreeStoreInterface
{
    /**
     * @var array
     */
    protected $tree;

    /**
     * @var string
     */
    protected $root;

    /**
     * @param array       $tree
     * @param null|string $root
     */
    public function __construct(array $tree = [], $root = null)
    {
        $this->tree = $tree;
        $this->root = $root;
    }

    /**
     * @param string,... $keyCollection
     *
     * @return mixed
     */
    public function get(...$keyCollection)
    {
        $keyCollection = $this->root === null ? $keyCollection : array_merge((array) $this->root, $keyCollection);

        return $this->resolveDeepSearch($this->tree, $keyCollection);
    }

    /**
     * @param array $tree
     * @param array $keyCollection
     *
     * @return null|mixed
     */
    protected function resolveDeepSearch(array $tree, array $keyCollection)
    {
        $key = array_shift($keyCollection);

        if (true !== array_key_exists($key, $tree)) {
            return null;
        } else if (false === (count($keyCollection) > 0)) {
            return $tree[$key];
        }

        return $this->resolveDeepSearch($tree[$key], $keyCollection);
    }
}

/* EOF */
