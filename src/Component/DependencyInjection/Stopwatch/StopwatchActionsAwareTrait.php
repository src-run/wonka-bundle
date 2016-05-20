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

namespace SR\WonkaBundle\Component\DependencyInjection\Aware;

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
        if ($this->hasStopwatch()) {
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
        if ($this->hasStopwatch()) {
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
        if ($this->hasStopwatch()) {
            $this->stopwatchEventSetAdd($name, $this->stopwatch->start($name, $category));
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
        if ($this->hasStopwatch()) {
            $this->stopwatchEventSetAdd($name, $this->stopwatch->stop($name));
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
        if ($this->hasStopwatch()) {
            $this->stopwatchEventSetAdd($name, $this->stopwatch->lap($name));
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
        if ($this->hasStopwatch()) {
            return $this->stopwatch->isStarted($name);
        }

        return false;
    }

    /**
     * @param string         $name
     * @param StopwatchEvent $event
     */
    protected function stopwatchEventSetAdd($name, StopwatchEvent $event)
    {
        if (!array_key_exists($name, (array) $this->stopwatchEventSet)) {
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
        return array_key_exists($name, $this->stopwatchEventSet) &&
               count($this->stopwatchEventSet[$name]) > 0;
    }

    /**
     * @param string $name
     *
     * @return null|StopwatchEvent[]
     */
    protected function getStopwatchEventSet($name)
    {
        if ($this->hasStopwatchEventSet($name)) {
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
        if ($this->hasStopwatchEventSet($name)) {
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
        if ($this->hasStopwatch()) {
            return $this->stopwatch->getSectionEvents($id);
        }

        return null;
    }
}

/* EOF */
