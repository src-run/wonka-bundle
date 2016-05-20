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

namespace SR\WonkaBundle\Utility\Config;

use SR\WonkaBundle\Component\DependencyInjection\Container\ContainerAwareInterface;

/**
 * Class ConfigInterface.
 */
interface ConfigInterface extends ContainerAwareInterface
{
    /**
     * @param string $parameterId
     *
     * @return null|mixed
     */
    public function get($parameterId);

    /**
     * @param string $parameterId
     *
     * @return bool
     */
    public function has($parameterId);
}

/* EOF */
