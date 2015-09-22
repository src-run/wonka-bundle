<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Component\DependencyInjection\Compiler;

use Scribe\Wonka\Exception\RuntimeException;
use Scribe\Wonka\Utility\ClassInfo;

/**
 * Class CompilerPassChainTrait.
 */
trait CompilerPassChainTrait
{
    /**
     * Collection of handler object instances registered to this chain.
     *
     * @var CompilerPassHandlerInterface[]
     */
    protected $handlers;

    /**
     * Force handler to implement more specific interface/subclass.
     *
     * @var string|null
     */
    protected $restrictions;

    /**
     * The mode to use when returning the result of {@see getHandler()}.
     *
     * @var int
     */
    protected $filterMode;

    /**
     * An available callable for the {@see addHandler()} method to call after it has completed its initial logic.
     *
     * @var null|callable
     */
    protected $addHandlerCallable;

    /**
     * Sets the handlers using the passed array. The array key is used as the priority and the array value must
     * be an instance of a handler.
     *
     * @param array $handlerCollection
     *
     * @return $this
     */
    public function setHandlerCollection(array $handlerCollection = [])
    {
        $this->clearHandlerCollection();

        foreach ($handlerCollection as $handler) {
            $this->addHandler($handler);
        }

        return $this;
    }

    /**
     * Gets the array of registered chain handlers.
     *
     * @return array
     */
    public function getHandlerCollection()
    {
        return (array) $this->handlers;
    }

    /**
     * Gets the array of registered chain handlers after filtering them using the provided callable.
     *
     * @param callable $filterCallable
     *
     * @return array
     */
    public function getHandlerCollectionFiltered(callable $filterCallable)
    {
        return (array) array_filter($this->handlers, $filterCallable);
    }

    /**
     * Checks if any handlers have been attached to this chains collection.
     *
     * @return bool
     */
    public function hasHandlerCollection()
    {
        return (bool) (empty($this->handlers) === false ?: false);
    }

    /**
     * Clear the handler collection array.
     *
     * @return $this
     */
    public function clearHandlerCollection()
    {
        $this->handlers = [];

        return $this;
    }

    /**
     * Basic implementation of the compiler pass add handler.
     *
     * @param CompilerPassHandlerInterface $handler
     * @param int|null                     $priority
     * @param mixed[]                      $extra
     *
     * @return $this
     */
    public function addHandler(CompilerPassHandlerInterface $handler, $priority = null, $extra = null)
    {
        if (false === $this->isHandlerValid($handler)) {
            return $this;
        }

        if (true === is_callable($this->addHandlerCallable)) {
            $callable = $this->addHandlerCallable;
            $callable($this->handlers, $handler, $this->determineHandlerPriority($handler, $priority), $extra);
        } else {
            $this->handlers[$this->determineHandlerPriority($handler, $priority)] = $handler;
        }
        ksort($this->handlers);

        return $this;
    }

    /**
     * Filters the handler collection based on their response to the passed criteria and then returns the resulting
     * handler(s) based on the filter mode set for the chain. These modes are defined in {@see CompilerPassChainInterface}
     * as constants and allow for three modes of operation: 1. Return the first supported handler; 2. Return the last
     * supported handler; 3. Return a handler only if the collection has been filtered down to a single item.
     *
     * @param mixed ...$by
     *
     * @throws RuntimeException If the mode {@see CompilerPassChainInterface::FILTER_MODE_SINGLE} is set and the filtered
     *                          handler collection contains more than one array element.
     *
     * @return CompilerPassHandlerInterface|null
     */
    public function getHandler(...$by)
    {
        $filteredCollection = $this->getHandlerCollectionFiltered(function (CompilerPassHandlerInterface $handler) use ($by) {
            return (bool) $handler->isSupported(...$by);
        });

        return 0 === count($filteredCollection) ?
            null : $this->getHandlerByMode($filteredCollection);
    }

    /**
     * Checks if the passed handler has been added to this chains collection.
     *
     * @param CompilerPassHandlerInterface $handler
     *
     * @return bool
     */
    public function hasHandler(CompilerPassHandlerInterface $handler)
    {
        return (bool) (false === in_array($handler, $this->handlers, false) ?: true);
    }

    /**
     * Checks if the passed handler class name has exists within this chain collection.
     *
     * @param string $search
     *
     * @return bool
     */
    public function hasHandlerType($search)
    {
        $handlerCollection = array_filter($this->handlers, function (CompilerPassHandlerInterface $handler) use ($search) {
            return (bool) ($handler->getType() === (string) $search ?: false);
        });

        return (bool) (0 === count($handlerCollection) ?: false);
    }

    /**
     * Returns a single handler from the passed handler collection based on the set filter mode. For more information
     * about available modes reference {@see getHandler()} documentation.
     *
     * @param array $filteredHandlerCollection
     *
     * @return CompilerPassHandlerInterface|null
     */
    private function getHandlerByMode(array $filteredHandlerCollection)
    {
        switch ($this->filterMode) {
            case CompilerPassChainInterface::FILTER_MODE_FIRST:

                return $this->getHandlerByModeFirst($filteredHandlerCollection);

            case CompilerPassChainInterface::FILTER_MODE_LAST:

                return $this->getHandlerByModeLast($filteredHandlerCollection);

            case CompilerPassChainInterface::FILTER_MODE_SINGLE:
            default:

                return $this->getHandlerByModeSingle($filteredHandlerCollection);
        }
    }

    /**
     * Helper function for {@see getHandlerByMode} for mode {@see CompilerPassChainInterface::FILTER_MODE_FIRST}.
     *
     * @param array $filteredHandlerCollection
     *
     * @return CompilerPassHandlerInterface
     */
    private function getHandlerByModeFirst(array $filteredHandlerCollection)
    {
        return array_first($filteredHandlerCollection);
    }

    /**
     * Helper function for {@see getHandlerByMode} for mode {@see CompilerPassChainInterface::FILTER_MODE_LAST}.
     *
     * @param array $filteredHandlerCollection
     *
     * @return CompilerPassHandlerInterface
     */
    private function getHandlerByModeLast(array $filteredHandlerCollection)
    {
        return array_last($filteredHandlerCollection);
    }

    /**
     * Helper function for {@see getHandlerByMode} for mode {@see CompilerPassChainInterface::FILTER_MODE_SINGLE}.
     *
     * @param array $filteredHandlerCollection
     *
     * @throws RuntimeException When passed collection contains more than a single array element.
     *
     * @return mixed
     */
    private function getHandlerByModeSingle(array $filteredHandlerCollection)
    {
        if (1 !== count($filteredHandlerCollection)) {
            throw new RuntimeException(
                'More than one handler contained in collection but the chain type "%s" is configured to allow '.
                'only a single handler to meet a given requirement, as passed to "%s::getHandler".', null, null,
                ClassInfo::getClassNameByInstance($this), get_called_class()
            );
        }

        return array_first($filteredHandlerCollection);
    }

    /**
     * Checks if the passed handler is valid based on the optional "instanceof" restriction set.
     *
     * @param CompilerPassHandlerInterface $handler
     *
     * @return bool
     */
    private function isHandlerValid(CompilerPassHandlerInterface $handler)
    {
        if (null === $this->restrictions || 0 === count($this->restrictions)) {
            return (bool) true;
        }

        foreach ($this->restrictions as $restriction) {
            if (false === ($handler instanceof $restriction)) {
                return (bool) false;
            }
        }

        return (bool) true;
    }

    /**
     * Get a valid priority. If a priority is tagged for the service, that value is returned, otherwise it returns a
     * priority beginning at the internal priority start value.
     *
     * @param CompilerPassHandlerInterface $handler
     * @param mixed                        $priority
     *
     * @return int
     */
    private function determineHandlerPriority(CompilerPassHandlerInterface $handler, $priority)
    {
        static $priorityInternal = CompilerPassChainInterface::PRIORITY_INTERNAL_START;

        if (null === $priority) {
            $priority = $priorityInternal++;
        }

        return (int) $priority;
    }

    /**
     * @return callable|null
     */
    public function getAddHandlerCallable()
    {
        return $this->addHandlerCallable;
    }

    /**
     * @param callable|null $addHandlerCallable
     *
     * @return $this
     */
    public function setAddHandlerCallable(callable $addHandlerCallable = null)
    {
        $this->addHandlerCallable = $addHandlerCallable;

        return $this;
    }
}

/* EOF */
