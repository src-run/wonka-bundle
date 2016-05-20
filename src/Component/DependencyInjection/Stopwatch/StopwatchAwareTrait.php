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

use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Trait StopwatchAwareTrait.
 */
trait StopwatchAwareTrait
{
    /**
     * @var Stopwatch|null
     */
    protected $stopwatch;

    /**
     * @return null|Stopwatch
     */
    public function getStopwatch()
    {
        return $this->stopwatch;
    }

    /**
     * @param Stopwatch $stopwatch
     *
     * @return $this
     */
    public function setStopwatch(Stopwatch $stopwatch = null)
    {
        $this->stopwatch = $stopwatch;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasStopwatch()
    {
        return $this->stopwatch instanceof Stopwatch;
    }
}

/* EOF */
