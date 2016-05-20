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

use SR\WonkaBundle\Component\HttpKernel\Kernel;

/**
 * Class AppKernel.
 */
class AppKernel extends Kernel
{
    /**
     */
    public function setup()
    {
        $this->register('\Symfony\Bundle\MonologBundle\MonologBundle');
        $this->register('\Symfony\Bundle\FrameworkBundle\FrameworkBundle');
        $this->register('\Symfony\Bundle\SecurityBundle\SecurityBundle');
        $this->register('\SR\WonkaBundle\WonkaBundle');
        $this->register('\Symfony\Bundle\DebugBundle\DebugBundle', 'dev', 'test');
    }
}

/* EOF */
