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

namespace Scribe\WonkaBundle\Component\DependencyInjection\Compiler\Attendant;

use Scribe\Wonka\Utility\ClassInfo;
use Scribe\Wonka\Utility\Mapper\ParametersToPropertiesMapperTrait;

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
     * @param array[],... $parameterSet
     */
    public function __construct(...$parameterSet)
    {
        $this->assignPropertyCollectionToSelf(...$parameterSet);
    }

    /**
     * Casting to string returns class name of attendant.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getType();
    }

    /**
     * Override to define logic for supported checks.
     *
     * @param mixed,... $by
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
     * @param false|bool $resolveQualifiedName
     *
     * @return string
     */
    public function getType($resolveQualifiedName = false)
    {
        return (string) ($resolveQualifiedName === true ? get_called_class() : ClassInfo::getClassName(get_called_class()));
    }
}

/* EOF */
