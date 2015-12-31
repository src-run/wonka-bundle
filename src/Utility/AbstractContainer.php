<?php

/*
 * This file is part of the Wonka Bundle.
 *
 * (c) Scribe Inc.     <scr@src.run>
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Utility;

/**
 * Class AbstractContainer.
 */
abstract class AbstractContainer implements \ArrayAccess
{
    /**
     * @var array
     */
    private $items = [];

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function __set($key, $value)
    {
        return $this->setItem($key, $value);
    }

    /**
     * @param string $key
     * @param array  $values
     *
     * @return $this|mixed|null
     */
    public function __call($key, $values)
    {
        if (count($values) === 0) {
            return $this->getItem($key);
        } else {
            return $this->setItem($key, $values[0]);
        }
    }

    /**
     * @param string $key
     *
     * @return null|mixed
     */
    public function __get($key)
    {
        return $this->getItem($key);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        if (!isset($this->items[(string) $key])) {
            return false;
        }
        if (empty($this->items[(string) $key])) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function __toArray()
    {
        return (array) $this->items;
    }

    /**
     * @param string $key
     */
    public function __unset($key)
    {
        $this->unsetItem($key);
    }

    /**
     * @param string|null $key
     * @param mixed       $value
     *
     * @return $this
     */
    public function setItem($key, $value)
    {
        if (is_null($key)) {
            $this->items[] = $value;
        } else {
            $this->items[(string) $key] = $value;
        }

        return $this;
    }

    /**
     * @param string $key
     *
     * @return null|mixed
     */
    public function getItem($key)
    {
        if ($this->hasItem($key)) {
            return $this->items[(string) $key];
        }

        return;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param string $key
     */
    public function unsetItem($key)
    {
        unset($this->items[(string) $key]);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasItem($key)
    {
        return array_key_exists((string) $key, $this->items);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function emptyItem($key)
    {
        $item = $this->getItem($key);

        return (bool) (count($item) > 0 ?: false);
    }

    /**
     * @param mixed $key
     * @param mixed $value
     */
    public function offsetSet($key, $value)
    {
        $this->setItem($key, $value);
    }

    /**
     * @param mixed $key
     */
    public function offsetUnset($key)
    {
        $this->unsetItem($key);
    }

    /**
     * @param mixed $key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->hasItem($key);
    }

    /**
     * @param mixed $key
     *
     * @return mixed|null
     */
    public function offsetGet($key)
    {
        return $this->getItem($key);
    }
}

/* EOF */
