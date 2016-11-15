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

use SR\Util\Info\ClassInfo;
use SR\WonkaBundle\Twig\Definition\AbstractTwigDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\Definition\TwigFilterDefinition;
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;

/**
 * Class TwigExtension.
 */
class TwigExtension extends \Twig_Extension implements TwigExtensionInterface
{
    /**
     * @var TwigOptionsDefinition
     */
    private $options = [];

    /**
     * @var TwigFilterDefinition[]
     */
    private $filters = [];

    /**
     * @var TwigFunctionDefinition[]
     */
    private $functions = [];

    /**
     * @param TwigOptionsDefinition|null $options
     * @param TwigFilterDefinition[]     $filters
     * @param TwigFunctionDefinition[]   $functions
     */
    public function __construct(TwigOptionsDefinition $options = null, array $filters = [], array $functions = [])
    {
        $this->setOptions($options === null ? new TwigOptionsDefinition() : $options);
        $this->addFilters($filters);
        $this->addFunctions($functions);
    }

    /**
     * @return TwigOptionsDefinition
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param TwigOptionsDefinition $options
     *
     * @return $this
     */
    public function setOptions(TwigOptionsDefinition $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Sets the options that tells Twig it should inject the Twig_Enviornment into the function call.
     *
     * @param bool $enable
     *
     * @return $this
     */
    public function addOptionNeedsEnvironment($enable = true)
    {
        $this->options->set('needs_environment', $enable);

        return $this;
    }

    /**
     * Sets the option that allows for HTML to be returned from the extension function.
     *
     * @param bool $enable
     *
     * @return $this
     */
    public function addOptionHtmlSafe($enable = true)
    {
        $this->options->set('is_safe', $enable ? ['html'] : []);

        return $this;
    }

    /**
     * Returns the name of the Twig extension based on the classname.
     *
     * @return string
     */
    final public function getName()
    {
        return strtolower('twig_extension_'.preg_replace('{twigextension$}i', '', ClassInfo::getNameShort(static::class)));
    }

    /**
     * Clears the array of extension functions.
     *
     * @return $this
     */
    final public function clearFunctions()
    {
        $this->functions = [];

        return $this;
    }

    /**
     * Returns an array of extension functions as {@see:\Twig_Function}.
     *
     * @return \Twig_Function[]
     */
    final public function getFunctions()
    {
        return $this->getNativeSet($this->functions);
    }

    /**
     * Adds an array of extension functions.
     *
     * @param TwigFunctionDefinition[] $functions
     *
     * @return $this
     */
    final public function addFunctions(array $functions = [])
    {
        $this->clearFunctions();

        foreach ($functions as $f) {
            if (!$f instanceof TwigFunctionDefinition || in_array($f, $this->functions)) {
                continue;
            }

            $this->functions[] = $f;
        }

        return $this;
    }

    /**
     * Add functions to extension.
     *
     * @param string                     $name
     * @param callable                   $callable
     * @param TwigOptionsDefinition|null $options
     *
     * @return $this
     */
    final public function addFunction($name, callable $callable, TwigOptionsDefinition $options = null)
    {
        $this->addFunctions([new TwigFunctionDefinition($name, $callable, $options)]);

        return $this;
    }

    /**
     * Clears the array of extension filters.
     *
     * @return $this
     */
    final public function clearFilters()
    {
        $this->filters = [];

        return $this;
    }

    /**
     * Returns an array of extension filters as {@see:\Twig_Filters}.
     *
     * @return \Twig_Filter[]
     */
    final public function getFilters()
    {
        return $this->getNativeSet($this->filters);
    }

    /**
     * Adds an array of extension filters.
     *
     * @param TwigFilterDefinition[] $filters
     *
     * @return $this
     */
    final public function addFilters(array $filters = [])
    {
        foreach ($filters as $f) {
            if (!$f instanceof TwigFilterDefinition || in_array($f, $this->filters)) {
                continue;
            }

            $this->filters[] = $f;
        }

        return $this;
    }

    /**
     * Add filter to extension.
     *
     * @param string                     $name
     * @param callable                   $callable
     * @param TwigOptionsDefinition|null $options
     *
     * @return $this
     */
    final public function addFilter($name, callable $callable, TwigOptionsDefinition $options = null)
    {
        $this->addFilters([new TwigFilterDefinition($name, $callable, $options)]);

        return $this;
    }

    /**
     * @param TwigFunctionDefinition[]|TwigFilterDefinition[] $items
     *
     * @return \Twig_Function[]|\Twig_Filter[]
     */
    final private function getNativeSet(array $items)
    {
        return array_map(function (AbstractTwigDefinition $f) {
            $f->getOptions()->merge($this->options);

            return $f->getNativeInstance();
        }, $items);
    }
}

/* EOF */
