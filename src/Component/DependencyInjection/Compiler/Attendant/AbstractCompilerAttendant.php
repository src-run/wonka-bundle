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

namespace SR\WonkaBundle\Component\DependencyInjection\Compiler\Attendant;

use SR\Reflection\Inspect;
use SR\Wonka\Utility\Mapper\ParametersToPropertiesMapperTrait;

/**
 * Class AbstractCompilerAttendant.
 */
abstract class AbstractCompilerAttendant implements CompilerAttendantInterface
{
    use ParametersToPropertiesMapperTrait;

    /**
     * Construct object with default parameters. Any number of parameters may be passed so long as
     * they are each a single-element associative array of the form [propertyName=>propertyValue].
     * If passed, these additional parameters will overwrite the default instance properties and,
     * as such, the chain runtime handling.
     *
     * @param array[],... $parameters
     */
    public function __construct(...$parameters)
    {
        $this->assignPropertyCollectionToSelf(...$parameters);
    }

    /**
     * Casting to string returns class name of attendant.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getType();
    }

    /**
     * Override to define logic for supported checks.
     *
     * @param mixed ...$by
     *
     * @return bool
     */
    public function isSupported(...$by)
    {
        return true;
    }

    /**
     * The attendants are designated their "type" using the class names of the respective object. Although
     * fully-qualified names can be (and are) used, this implementation does take the assuption that the
     * base names of the implementations does not overlap.
     *
     * @param bool $qualified
     *
     * @return string
     */
    public function getType($qualified = false)
    {
        return Inspect::thisInstance($this)->name($qualified);
    }
}

/* EOF */
