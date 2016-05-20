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

namespace SR\WonkaBundle\Component\Templating;

/**
 * Class TwigExtensionInterface.
 */
interface TwigExtensionInterface
{
    /**
     * @var string
     */
    const PROPERTY_PART_OPTION = 'OptionCollection';

    /**
     * @var string
     */
    const PROPERTY_PART_METHOD = 'CallableCollection';
}

/* EOF */
