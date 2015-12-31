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

namespace Scribe\WonkaBundle\Utility\Config;

use Scribe\WonkaBundle\Component\DependencyInjection\Container\ContainerAwareInterface;

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
