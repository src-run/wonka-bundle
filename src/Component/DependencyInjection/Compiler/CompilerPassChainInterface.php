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

/**
 * Class CompilerPassChainInterface.
 */
interface CompilerPassChainInterface
{
    /**
     * The starting priority for handlers attached without a defined priority.
     *
     * @var int
     */
    const PRIORITY_INTERNAL_START = 1000;

    /**
     * Mode used when calling {@see getHandler()} to determine which handler is returned. This mode returns the
     * first handler that reports back it is supported.
     *
     * @var int
     */
    const FILTER_MODE_FIRST = 1;

    /**
     * Mode used when calling {@see getHandler()} to determine which handler is returned. This mode returns the
     * last handler that reports it is supported.
     *
     * @var int
     */
    const FILTER_MODE_LAST = 2;

    /**
     * Mode used when calling {@see getHandler()} to determine which handler is returned. This mode will only return
     * a handler if the filter resulted in a singular result, otherwise it will throw an exception.
     *
     * @var int
     */
    const FILTER_MODE_SINGLE = 3;

    /**
     * The default restriction to check against when adding handler.
     *
     * @var string
     */
    const RESTRICTION_INTERFACE_DEFAULT = CompilerPassHandlerInterface::INTERFACE_NAME;

    /**
     * Basic implementation of the compiler pass add handler.
     *
     * @param CompilerPassHandlerInterface $handler
     * @param int|null                     $priority
     *
     * @return $this
     */
    public function addHandler(CompilerPassHandlerInterface $handler, $priority = null);

    /**
     * Basic implementation of the get handler based on criteria passed.
     *
     * @param mixed ...$by
     *
     * @return CompilerPassHandlerInterface|null
     */
    public function getHandler(...$by);

    /**
     * Checks if the passed handler has been added to this chains collection.
     *
     * @param CompilerPassHandlerInterface $handler
     *
     * @return bool
     */
    public function hasHandler(CompilerPassHandlerInterface $handler);

    /**
     * Checks if the passed handler class name has exists within this chain collection.
     *
     * @param string $search
     *
     * @return bool
     */
    public function hasHandlerType($search);

    /**
     * Sets the handlers using the passed array. The array key is used as the priority and the array value must
     * be an instance of a handler.
     *
     * @param array $handlerCollection
     *
     * @return $this
     */
    public function setHandlerCollection(array $handlerCollection = []);

    /**
     * Gets the array of registered chain handlers.
     *
     * @return array
     */
    public function getHandlerCollection();

    /**
     * Gets the array of registered chain handlers after filtering them using the provided callable.
     *
     * @param callable $filterCallable
     *
     * @return array
     */
    public function getHandlerCollectionFiltered(callable $filterCallable);

    /**
     * Checks if any handlers have been attached to this chains collection.
     *
     * @return bool
     */
    public function hasHandlerCollection();

    /**
     * Clear the handler collection array.
     *
     * @return $this
     */
    public function clearHandlerCollection();
}

/* EOF */
