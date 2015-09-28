<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Component\Templating;

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
