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
class TwigOptionsDefinition
{
    /**
     * @var mixed[]
     */
    private $elements = [];

    /**
     * @param mixed[] $elements
     */
    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }

    /**
     * @param mixed[] $elements
     *
     * @return TwigOptionsDefinition
     */
    public static function create(array $elements = [])
    {
        return new static($elements);
    }

    /**
     * @return mixed[]
     */
    public function toArray()
    {
        return $this->elements;
    }

    /**
     * @param mixed $key
     *
     * @return mixed
     */
    public function remove($key)
    {
        if (!$this->containsKey($key)) {
            return null;
        }

        $unset = $this->elements[$key];
        unset($this->elements[$key]);

        return $unset;
    }

    /**
     * @param mixed $element
     *
     * @return bool
     */
    public function removeElement($element)
    {
        if (null !== ($key = $this->indexOf($element))) {
            $this->remove($key);

            return true;
        }

        return false;
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->containsKey($offset);
    }

    /**
     * @param mixed $offset
     *
     * @return null|mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @return $this
     */
    public function offsetSet($offset, $value)
    {
        if (!isset($offset)) {
            return $this->add($value);
        }

        return $this->set($offset, $value);
    }

    /**
     * @param mixed $offset
     *
     * @return $this
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }

    /**
     * @param mixed $key
     *
     * @return bool
     */
    public function containsKey($key)
    {
        return array_key_exists($key, $this->elements);
    }

    /**
     * @param mixed $element
     *
     * @return bool
     */
    public function contains($element)
    {
        return in_array($element, $this->elements, true);
    }

    /**
     * @param TwigOptionsDefinition[] $collections
     *
     * @return bool
     */
    public function equitable(TwigOptionsDefinition ...$collections)
    {
        $_ = function ($a, $b) {
            return $a > $b;
        };

        $valid = $this->sortByKeys($_);

        $check = array_filter($collections, function (TwigOptionsDefinition $v) use ($_, $valid) {
            $assertion = $v->sortByKeys($_);

            return $assertion->toArray() === $valid->toArray();
        });

        return count($collections) === count($check);
    }

    /**
     * @param \Closure $predicate
     *
     * @return bool
     */
    public function exists(\Closure $predicate)
    {
        foreach ($this->elements as $key => $element) {
            if ($predicate($key, $element)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $element
     *
     * @return null|mixed
     */
    public function indexOf($element)
    {
        if (false !== ($key = array_search($element, $this->elements, true))) {
            return $key;
        }

        return null;
    }

    /**
     * @param mixed $key
     *
     * @return null|mixed
     */
    public function get($key)
    {
        if ($this->containsKey($key)) {
            return $this->elements[$key];
        }

        return null;
    }

    /**
     * @return mixed[]
     */
    public function getKeys()
    {
        return array_keys($this->elements);
    }

    /**
     * @return mixed[]
     */
    public function getValues()
    {
        return array_values($this->elements);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * @param mixed $search
     *
     * @return int
     */
    public function instancesOf($search)
    {
        $elements = $this->elements;
        $elements = array_filter($elements, function ($v) use ($search) {
            return $v === $search;
        });

        return count($elements);
    }

    /**
     * @param mixed $key
     * @param mixed $element
     *
     * @return $this
     */
    public function set($key, $element)
    {
        $this->elements[$key] = $element;

        return $this;
    }

    /**
     * @param mixed $element
     *
     * @return $this
     */
    public function add($element)
    {
        $this->elements[] = $element;

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
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * @param \Closure $closure
     *
     * @return TwigOptionsDefinition
     */
    public function map(\Closure $closure)
    {
        return self::create(array_map($closure, $this->elements));
    }

    /**
     * @param \Closure $predicate
     * @param int      $flag
     *
     * @return TwigOptionsDefinition
     */
    public function filter(\Closure $predicate, $flag = ARRAY_FILTER_USE_BOTH)
    {
        $elements = $this->elements;

        return self::create(array_filter($elements, $predicate, $flag));
    }

    /**
     * @param \Closure $predicate
     *
     * @return TwigOptionsDefinition
     */
    public function filterByKeys(\Closure $predicate)
    {
        return $this->filter($predicate, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param \Closure $predicate
     *
     * @return bool
     */
    public function forAll(\Closure $predicate)
    {
        foreach ($this->elements as $key => $element) {
            if (!$predicate($key, $element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Closure $predicate
     *
     * @return TwigOptionsDefinition[]
     */
    public function partition(\Closure $predicate)
    {
        $a = $b = [];

        foreach ($this->elements as $key => $element) {
            if ($predicate($element, $key)) {
                $a[$key] = $element;
            } else {
                $b[$key] = $element;
            }
        }

        return [self::create($a), self::create($b)];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s@%s', __CLASS__, spl_object_hash($this));
    }

    /**
     * @return $this
     */
    public function clear()
    {
        $this->elements = [];

        return $this;
    }

    /**
     * @param int      $offset
     * @param null|int $length
     *
     * @return TwigOptionsDefinition
     */
    public function slice($offset, $length = null)
    {
        $set = $this->elements;

        return self::create(array_splice($set, $offset, $length));
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
        foreach ($options->toArray() as $name => $option) {
            if (array_key_exists($name, $this->elements) && $overwrite === false) {
                continue;
            }

            $this->set($name, $option);
        }

        return $this;
    }



    /**
     * @return TwigOptionsDefinition
     */
    public function reverse()
    {
        return self::create(array_reverse($this->elements, true));
    }

    /**
     * @return TwigOptionsDefinition
     */
    public function shuffle()
    {
        $elements = [];
        $elementKeys = array_keys($this->elements);

        shuffle($elementKeys);

        foreach ($elementKeys as $key) {
            $elements[$key] = $this->elements[$key];
        }

        return self::create($elements);
    }

    /**
     * @param \Closure $predicate
     *
     * @return TwigOptionsDefinition
     */
    public function sort(\Closure $predicate)
    {
        $elements = $this->elements;

        uasort($elements, $predicate);

        return self::create($elements);
    }

    /**
     * @param \Closure $predicate
     *
     * @return TwigOptionsDefinition
     */
    public function sortByKeys(\Closure $predicate)
    {
        $elements = $this->elements;

        uksort($elements, $predicate);

        return self::create($elements);
    }
}

/* EOF */
