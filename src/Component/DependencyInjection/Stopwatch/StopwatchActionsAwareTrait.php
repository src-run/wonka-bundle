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

namespace Scribe\WonkaBundle\Component\DependencyInjection\Aware;

use Symfony\Component\Stopwatch\StopwatchEvent;

/**
 * Trait StopwatchActionsAwareTrait.
 */
trait StopwatchActionsAwareTrait
{
    use StopwatchAwareTrait;

    /**
     * @var string[]
     */
    private $stopwatchEventSet;

    /**
     * @param string|null $id
     *
     * @return $this
     */
    protected function stopwatchOpenSection($id = null)
    {
        if (true === $this->hasStopwatch()) {
            $this->stopwatch->openSection($id);
        }

        return $this;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    protected function stopwatchStopSection($id)
    {
        if (true === $this->hasStopwatch()) {
            $this->stopwatch->stopSection($id);
        }

        return $this;
    }

    /**
     * @param string      $name
     * @param string|null $category
     *
     * @return $this
     */
    protected function stopwatchStart($name, $category = null)
    {
        if (true === $this->hasStopwatch()) {
            $this->stopwatchEventSetAdd(
                $name,
                $this->stopwatch->start($name, $category)
            );
        }

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    protected function stopwatchStop($name)
    {
        if (true === $this->hasStopwatch()) {
            $this->stopwatchEventSetAdd(
                $name,
                $this->stopwatch->stop($name)
            );
        }

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    protected function stopwatchLap($name)
    {
        if (true === $this->hasStopwatch()) {
            $this->stopwatchEventSetAdd(
                $name,
                $this->stopwatch->lap($name)
            );
        }

        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function isStopwatchStarted($name)
    {
        if (true === $this->hasStopwatch()) {
            return (bool) $this->stopwatch->isStarted($name);
        }

        return false;
    }

    /**
     * @param string         $name
     * @param StopwatchEvent $event
     */
    protected function stopwatchEventSetAdd($name, StopwatchEvent $event)
    {
        if (false === array_key_exists($name, (array) $this->stopwatchEventSet)) {
            $this->stopwatchEventSet[$name] = [];
        }

        $this->stopwatchEventSet[$name][] = $event;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function hasStopwatchEventSet($name)
    {
        return (bool) (true === array_key_exists($name, $this->stopwatchEventSet) && true === (count($this->stopwatchEventSet[$name]) > 0));
    }

    /**
     * @param string $name
     *
     * @return null|StopwatchEvent[]
     */
    protected function getStopwatchEventSet($name)
    {
        if (true === $this->hasStopwatchEventSet($name)) {
            return $this->stopwatchEventSet[$name];
        }

        return null;
    }

    /**
     * @param string $name
     *
     * @return null|StopwatchEvent
     */
    protected function getStopwatchEventLast($name)
    {
        if (true === $this->hasStopwatchEventSet($name)) {
            return getLastArrayElement($this->stopwatchEventSet[$name]);
        }

        return null;
    }

    /**
     * @param string $id
     *
     * @return null|StopwatchEvent[]
     */
    protected function getStopwatchSectionEvents($id)
    {
        if (true === $this->hasStopwatch()) {
            return $this->stopwatch->getSectionEvents($id);
        }

        return null;
    }
}

/* EOF */
