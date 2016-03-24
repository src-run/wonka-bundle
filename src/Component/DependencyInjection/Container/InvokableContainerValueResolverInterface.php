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

namespace Scribe\WonkaBundle\Component\DependencyInjection\Container;

use Symfony\Component\DependencyInjection\ContainerAwareInterface as SymfonyContainerAwareInterface;

/**
 * Class InvokableContainerValueResolverInterface.
 */
interface InvokableContainerValueResolverInterface extends SymfonyContainerAwareInterface
{
    /**
     * Invoking this class using function syntax, while providing either a
     * parameter or service name lookup, will return the requested value.
     * Begin all parameter lookups with '%' (and optionally close with the same)
     * to search for parameters. For services, they are the default and require
     * no syntax, BUT using the symfony syntax of begining service names with
     * a '@' is supported and suggested for clarity.
     *
     * @param string $lookup
     *
     * @return mixed
     */
    public function __invoke($lookup);
}

/* EOF */
