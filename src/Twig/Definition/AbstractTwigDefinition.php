<?php

/*
 * This file is part of the `src-run/wonka-bundle` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\WonkaBundle\Twig\Definition;

/**
 * Class TwigFunctionDefinition.
 */
abstract class AbstractTwigDefinition
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Twig_Function|\Twig_Filter
     */
    protected $callable;

    /**
     * @var TwigOptionsDefinition
     */
    protected $options;

    /**
     * @param string|null   $name
     * @param callable|null $callable
     */
    public function __construct($name = null, callable $callable = null, TwigOptionsDefinition $options = null)
    {
        if ($name !== null && $callable !== null) {
            $this->setName($name);
            $this->setCallable($callable);
        }

        $this->setOptions($options ?: new TwigOptionsDefinition());
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param callable $callable
     *
     * @return $this
     */
    public function setCallable(callable $callable)
    {
        $this->callable = $callable;

        return $this;
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
     * @return TwigOptionsDefinition
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return \Twig_Function|\Twig_Filter
     */
    abstract public function getNativeInstance();
}

/* EOF */
