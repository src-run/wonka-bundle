<?php

/*
 * This file is part of the `src-run/wonka-bundle` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\WonkaBundle\Twig\Definition;

/**
 * Class TwigOptionsDefinition.
 */
class TwigOptionsDefinition implements \IteratorAggregate, \Countable
{
    /**
     * @var mixed[]
     */
    private $options = [];

    /**
     * @param mixed[] $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @param mixed[] $options
     *
     * @return TwigOptionsDefinition
     */
    public static function create(array $options = [])
    {
        return new static($options);
    }

    /**
     * @param mixed $key
     * @param mixed $option
     *
     * @return $this
     */
    public function set($key, $option)
    {
        $this->options[$key] = $option;

        return $this;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function containsKey($key)
    {
        return array_key_exists($key, $this->options);
    }

    /**
     * Merges the passed options, optionally overwriting duplicate options or
     * keeping existing options.
     *
     * @param TwigOptionsDefinition $options
     * @param bool                  $overwrite
     *
     * @return $this
     */
    public function merge(TwigOptionsDefinition $options, $overwrite = true)
    {
        if ($options->isEmpty()) {
            return $this;
        }

        foreach ($options as $name => $option) {
            if ($this->containsKey($name) && $overwrite === false) {
                continue;
            }

            $this->set($name, $option);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->count() === 0;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->options);
    }

    /**
     * @return mixed[]
     */
    public function toArray()
    {
        return $this->options;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->options);
    }
}

/* EOF */
