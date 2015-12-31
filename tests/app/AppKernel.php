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

use Scribe\WonkaBundle\Component\HttpKernel\Kernel;

/**
 * Class AppKernel.
 */
class AppKernel extends Kernel
{
    /**
     */
    public function setup()
    {
        $this
            ->addBundle('\Symfony\Bundle\MonologBundle\MonologBundle')
            ->addBundle('\Symfony\Bundle\FrameworkBundle\FrameworkBundle')
            ->addBundle('\Symfony\Bundle\SecurityBundle\SecurityBundle')
            ->addBundle('\Scribe\WonkaBundle\ScribeWonkaBundle')
            ->addBundle('\Symfony\Bundle\DebugBundle\DebugBundle', 'dev', 'test');
    }
}

/* EOF */
