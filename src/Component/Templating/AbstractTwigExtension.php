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

use SR\Exception\RuntimeException;
use SR\Utility\ClassInspect;

/**
 * Class AbstractTwigExtension.
 */
abstract class AbstractTwigExtension extends \Twig_Extension implements TwigExtensionInterface
{
    /**
     * Options that are globally applied to all filters and functions for this extension.
     *
     * @var array[]
     */
    private $optionCollection = [];

    /**
     * Options that are specific to a specific twig filter for this extension.
     *
     * @var array[]
     */
    private $filterOptionCollection = [];

    /**
     * Options that are specific to a specific twig function for this extension.
     *
     * @var array[]
     */
    private $functionOptionCollection = [];

    /**
     * An associative array of filters this extension will register. The array key represents the filter
     * name registered with Twig and the value is a callable.
     *
     * @var callable[]
     */
    private $filterCallableCollection;

    /**
     * An associative array of functions this extension will register. The array key represents the function
     * name registered with Twig and the value is a callable.
     *
     * @var callable[]
     */
    private $functionCallableCollection;

    /**
     * Initialize the object.
     *
     * @param array $optionCollection
     * @param bool  $htmlSafe
     */
    public function __construct(array $optionCollection = [], $htmlSafe = true)
    {
        $this
            ->setOptions($optionCollection)
        ;

        if (true === $htmlSafe) {
            $this->enableOptionHtmlSafe();
        }
    }

    /**
     * Returns the name of the Twig extension based on the classname.
     *
     * @return string
     */
    final public function getName()
    {
        return strtolower(sprintf('twig_ext_%s', ClassInspect::nameUnQualified($this)));
    }

    /**
     * Clears the options applied to all functions/filters this extension registers.
     *
     * @return $this
     */
    public function clearOptions()
    {
        $this->optionCollection = [];

        return $this;
    }

    /**
     * Clears and set the options applied to all functions/filters this extension registers.
     *
     * @param array[] $optionCollection
     *
     * @return $this
     */
    public function setOptions(array ...$optionCollection)
    {
        return $this
            ->clearOptions()
            ->stackOptions($optionCollection)
            ;
    }

    /**
     * Adds option(s) applied to all functions/filters this extension registers. If an option of the same key
     * already exists it is overridden.
     *
     * @param array[] $optionCollection
     *
     * @return $this
     */
    public function addOptions(array ...$optionCollection)
    {
        return $this->stackOptions($optionCollection);
    }

    /**
     * Internal implementation for setting the globally applied options for {@see setOptions()} and {@see addOptions()}.
     *
     * @param array $optionCollection
     *
     * @return $this
     */
    private function stackOptions(array $optionCollection)
    {
        foreach ($optionCollection as $option) {
            if (false === is_array($option) || 0 === count($option)) {
                continue;
            }

            $this->optionCollection[(string) key($option)] = current($option);
        }

        return $this;
    }

    /**
     * @param string $type
     * @param string $name
     *
     * @return array|\array[]
     */
    private function resolvedOptions($type, $name)
    {
        $optionCollection = $this->optionCollection;
        $typeOptionCollectionName = $type.self::PROPERTY_PART_OPTION;

        if (array_key_exists($name, $this->functionCallableCollection)) {
            $optionCollection = array_merge(
                $optionCollection,
                $this->{$typeOptionCollectionName}[$name]
            );
        }

        return $optionCollection;
    }

    /**
     * Sets the options that tells Twig it should inject the Twig_Enviornment into the function call.
     *
     * @return $this
     */
    public function enableOptionNeedsEnv()
    {
        $this->addOptions(
            ['needs_environment' => true]
        );

        return $this;
    }

    /**
     * Sets the option that allows for HTML to be returned from the extension function.
     *
     * @return $this
     */
    public function enableOptionHtmlSafe()
    {
        $this->addOptions(
            ['is_safe' => ['html']]
        );

        return $this;
    }

    /**
     * Add a Twig function to this extension.
     *
     * @param string   $name
     * @param callable $method
     * @param array    $optionCollection
     *
     * @return $this
     */
    public function addFunction($name, callable $method, array $optionCollection = [])
    {
        return $this
            ->stackCallable(__FUNCTION__, $name, $method, $optionCollection)
            ;
    }

    /**
     * Add a Twig filter to this extension.
     *
     * @param string   $name
     * @param callable $method
     * @param array    $optionCollection
     *
     * @return $this
     */
    public function addFilter($name, callable $method, array $optionCollection = [])
    {
        return $this
            ->stackCallable(__FUNCTION__, $name, $method, $optionCollection)
            ;
    }

    /**
     * Internal common implementation for {@see addFunction()} and {@see addFilter()}.
     *
     * @param string   $type
     * @param string   $name
     * @param callable $method
     * @param array    $optionCollection
     *
     * @return $this
     */
    private function stackCallable($type, $name, callable $method, array $optionCollection)
    {
        $this
            ->validateName($name)
            ->validateType($type)
        ;

        $optionVariableString = $type.self::PROPERTY_PART_OPTION;
        $methodVariableString = $type.self::PROPERTY_PART_METHOD;

        $this->{$optionVariableString}[$name] = $optionCollection;
        $this->{$methodVariableString}[$name] = $method;

        return $this;
    }

    /**
     * Validate and get the proper call type.
     *
     * @param mixed $type
     *
     * @return $this
     */
    private function validateType(&$type)
    {
        $this->validateName($type);
        $type = strtolower(substr($type, 3));

        return $this;
    }

    /**
     * Verify that a string was passed as the filter/function name.
     *
     * @param mixed $name
     *
     * @return $this
     */
    private function validateName($name)
    {
        if (true === is_string($name)) {
            return $this;
        }

        throw RuntimeException::create()
            ->setMessage('Invalid function/filter name provided to $s (you must provide a string).')
            ->with(__METHOD__);
    }

    /**
     * @param string $type
     *
     * @return array
     */
    private function getCallableCollectionForType($type)
    {
        $this->validateType($type);
        $type = substr($type, 0, strlen($type) - 1);

        $callableCollection = [];
        $callableCollectionName = $type.self::PROPERTY_PART_METHOD;
        $twigExtensionClassName = 'Twig_Simple'.ucfirst($type);

        if (false === is_array($this->{$callableCollectionName}) || 0 === count($this->{$callableCollectionName})) {
            return $callableCollection;
        }

        foreach ($this->{$callableCollectionName} as $name => $callable) {
            $callableCollection[$name] = new $twigExtensionClassName(
                $name, $callable, $this->resolvedOptions($type, $name)
            );
        }

        return $callableCollection;
    }

    /**
     * Returns an array of {@see Twig_SimpleFunction} instances, providing the beidge between twig function calls
     * and the methods in this class.
     *
     * @return array
     */
    public function getFunctions()
    {
        return $this->getCallableCollectionForType(__FUNCTION__);
    }

    /**
     * Returns an array of {@see Twig_SimpleFilter} instances, providing the beidge between twig function calls
     * and the methods in this class.
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->getCallableCollectionForType(__FUNCTION__);
    }
}

/* EOF */
