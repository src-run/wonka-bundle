<?php

/*
 * This file is part of the `src-run/wonka-bundle` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\WonkaBundle\Twig;

use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\Definition\TwigFilterDefinition;
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;

/**
 * Interface TwigExtensionInterface.
 */
interface TwigExtensionInterface
{
    /**
     * @return TwigOptionsDefinition
     */
    public function getOptions();

    /**
     * @param TwigOptionsDefinition $options
     *
     * @return $this
     */
    public function setOptions(TwigOptionsDefinition $options);

    /**
     * Sets the options that tells Twig it should inject the Twig_Enviornment into the function call.
     *
     * @param bool $enable
     *
     * @return $this
     */
    public function addOptionNeedsEnvironment($enable = true);

    /**
     * Sets the option that allows for HTML to be returned from the extension function.
     *
     * @param bool $enable
     *
     * @return $this
     */
    public function addOptionHtmlSafe($enable = true);

    /**
     * Returns the name of the Twig extension based on the classname.
     *
     * @return string
     */
    public function getName();

    /**
     * Clears the array of extension functions.
     *
     * @return $this
     */
    public function clearFunctions();

    /**
     * Returns an array of extension functions as {@see:\Twig_Function}.
     *
     * @return \Twig_Function[]
     */
    public function getFunctions();

    /**
     * Adds an array of extension functions.
     *
     * @param TwigFunctionDefinition[] $functions
     *
     * @return $this
     */
    public function addFunctions(array $functions = []);

    /**
     * Add functions to extension.
     *
     * @param string                     $name
     * @param callable                   $callable
     * @param TwigOptionsDefinition|null $options
     *
     * @return $this
     */
    public function addFunction($name, callable $callable, TwigOptionsDefinition $options = null);

    /**
     * Clears the array of extension filters.
     *
     * @return $this
     */
    public function clearFilters();

    /**
     * Returns an array of extension filters as {@see:\Twig_Filters}.
     *
     * @return \Twig_Filter[]
     */
    public function getFilters();

    /**
     * Adds an array of extension filters.
     *
     * @param TwigFilterDefinition[] $filters
     *
     * @return $this
     */
    public function addFilters(array $filters = []);

    /**
     * Add filter to extension.
     *
     * @param string                     $name
     * @param callable                   $callable
     * @param TwigOptionsDefinition|null $options
     *
     * @return $this
     */
    public function addFilter($name, callable $callable, TwigOptionsDefinition $options = null);
}

/* EOF */
