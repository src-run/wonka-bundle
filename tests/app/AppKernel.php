<?php

/*
 * This file is part of the `src-run/wonka-bundle` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use SR\WonkaBundle\Component\HttpKernel\Kernel;

/**
 * Class AppKernel.
 */
class AppKernel extends Kernel
{
    /**
     * {@inherit-doc}.
     */
    public static function setupDefinitions()
    {
        static::register(\Symfony\Bundle\MonologBundle\MonologBundle::class);
        static::register(\Symfony\Bundle\FrameworkBundle\FrameworkBundle::class);
        static::register(\Symfony\Bundle\SecurityBundle\SecurityBundle::class);
        static::register(\SR\WonkaBundle\WonkaBundle::class);

        static::register(\Symfony\Bundle\DebugBundle\DebugBundle::class)
            ->environments(self::ENV_DEV, self::ENV_TEST);
    }
}

/* EOF */
