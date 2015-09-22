<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Component\DependencyInjection\Compiler;

use Scribe\Wonka\Utility\ClassInfo;
use Scribe\Wonka\Utility\Mapper\ParametersToPropertiesMapperTrait;

/**
 * Class AbstractCompilerPassHandler.
 */
abstract class AbstractCompilerPassHandler implements CompilerPassHandlerInterface
{
    use ParametersToPropertiesMapperTrait;

    /**
     * Construct object with default parameters. Any number of parameters may be passed so long as they are each a
     * single-element associative array of the form [propertyName=>propertyValue]. If passed, these additional
     * parameters will overwrite the default instance properties and, as such, the chain runtime handling.
     *
     * @param array[] ...$parameters
     */
    public function __construct(...$parameters)
    {
        $this->assignPropertyCollectionToSelf(...$parameters);
    }

    /**
     * This must be overridden to enable support for this handler in the class inheriting from this abstract implementation.
     *
     * @param mixed ...$by
     *
     * @return bool
     */
    public function isSupported(...$by)
    {
        return false;
    }

    /**
     * Get the handler type (generally this will return the class name).
     *
     * @param bool $fqcn
     *
     * @return string
     */
    public function getType($fqcn = false)
    {
        return (string) ($fqcn === true ?
            ClassInfo::getNamespaceByInstance($this).'\\'.ClassInfo::getClassNameByInstance($this) :
            ClassInfo::getClassNameByInstance($this)
        );
    }
}

/* EOF */
