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

namespace SR\WonkaBundle\Component\DependencyInjection;

use SR\Exception\Runtime\RuntimeException;
use SR\Util\Info\ArrayInfo;
use SR\WonkaBundle\Component\DependencyInjection\Container\ContainerAwareInterface;
use SR\WonkaBundle\Component\DependencyInjection\Container\ContainerAwareTrait;
use SR\WonkaBundle\Component\DependencyInjection\Loader\XmlFileLoader;
use SR\WonkaBundle\Component\DependencyInjection\Loader\YamlFileLoader;
use SR\WonkaBundle\Component\HttpKernel\Bundle\BundleInspect;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension as BaseExtension;

/**
 * Class Extension.
 */
class Extension extends BaseExtension implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Service files to load.
     *
     * @var string[]
     */
    private $serviceFiles = [
        'services.yml',
    ];

    /**
     * Index prefix property.
     *
     * @var string
     */
    private $indexPrefix = 'abc';

    /**
     * Index parts separator.
     *
     * @var string
     */
    private $indexSeparator = '.';

    /**
     * Set the service files to load.
     *
     * @param array $files
     *
     * @return $this
     */
    protected function setServiceFiles(array $files = [])
    {
        $this->serviceFiles = $files;

        return $this;
    }

    /**
     * Get the service files to load.
     *
     * @return string[]
     */
    protected function getServiceFiles()
    {
        return $this->serviceFiles;
    }

    /**
     * Setter for index prefix.
     *
     * @param string $prefix
     *
     * @return $this
     */
    protected function setIndexPrefix($prefix)
    {
        $this->indexPrefix = (string) $prefix;

        return $this;
    }

    /**
     * Getter for index prefix.
     *
     * @return string
     */
    protected function getIndexPrefix()
    {
        return (string) $this->indexPrefix;
    }

    /**
     * Setter for index parts separator.
     *
     * @param string $separator
     *
     * @return $this
     */
    protected function setIndexSeparator($separator)
    {
        $this->indexSeparator = (string) $separator;

        return $this;
    }

    /**
     * Getter for index parts separator.
     *
     * @return string
     */
    protected function getIndexSeparator()
    {
        return (string) $this->indexSeparator;
    }

    /**
     * @return string[]
     */
    protected function getNamespaceAndVendorAndBundleName()
    {
        $caller = get_class($this);

        list($namespace, $vendor, $bundle) = BundleInspect::getContextFromNamespace($caller);

        return [$namespace, $vendor, $bundle];
    }

    /**
     * Loads the configuration (builds the container).
     *
     * @param array            $configs   collection of configs to load
     * @param ContainerBuilder $container symfony config container
     *
     * @return $this
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        list($namespace, $vendor, $bundle) = $this->getNamespaceAndVendorAndBundleName();
        $configQualified = sprintf('%s\DependencyInjection\Configuration', $namespace);

        $this->autoLoad($configs, $container, new $configQualified(), $vendor.'.'.$bundle);

        return $this;
    }

    /**
     * Determines if bundle is enabled. Intended to be used with the following config options:
     * - {@see \Symfony/Component/Config/Definition/Builder/ArrayNodeDefinition::canBeDisabled()})
     * - {@see \Symfony/Component/Config/Definition/Builder/ArrayNodeDefinition::canBeEnabled()})
     * Extend your bundle extension class from the following implementations to handle automatically:
     * - {@see \SR\WonkaBundle\Component\DependencyInjection\EnableableExtension}
     * - {@see \SR\WonkaBundle\Component\DependencyInjection\DisableableExtension}.
     *
     * @param array|null $configSet
     *
     * @return bool|null
     */
    final protected function isEnabled(array $configSet = null)
    {
        return isset($configSet[0]) && isset($configSet[0]['enabled']) ? $configSet[0]['enabled'] : false;
    }

    /**
     * Helper method to be called from load method ({@see load}) that automate the tedious task of parsing the config
     * tree to container parameter as well as loading any required service definition files.
     *
     * @param array                  $configSet
     * @param ContainerBuilder       $container
     * @param ConfigurationInterface $configuration
     * @param string|null            $prefix
     *
     * @return $this
     */
    final protected function autoLoad(
        array $configSet,
        ContainerBuilder $container,
        ConfigurationInterface $configuration,
        $prefix = null
    ) {
        $this->setContainer($container);
        $this->autoLoadConfiguration($configSet, $configuration, $prefix);
        $this->autoLoadServices($container, $configSet);

        return $this;
    }

    /**
     * Process the configuration and then load the resulting multi-dimensional {@see $configs} array to useful container
     * parameter indexes with their respective values set.
     *
     * @param array                  $configSet
     * @param ConfigurationInterface $configuration
     * @param string|null            $prefix
     *
     * @return $this
     */
    protected function autoLoadConfiguration(array $configSet, ConfigurationInterface $configuration, $prefix = null)
    {
        if (null !== $prefix) {
            $this->setIndexPrefix($prefix);
        }

        $this->processConfigsToParameters($this->processConfiguration($configuration, $configSet), null, false);

        return $this;
    }

    /**
     * Load all the services by iterating over the {@see $this->serviceFiles} defined at runtime; Yaml or XML based.
     *
     * @param ContainerBuilder $container
     * @param array|null       $config
     *
     * @return $this
     */
    protected function autoLoadServices(ContainerBuilder $container, array $config = null)
    {
        $resolvedDirectory = $this->resolveBundleDirectory($container);

        foreach ($this->getServiceFiles() as $file) {
            $loader = $this->getFileLoader($file);
            $loader->setup($container, new FileLocator($resolvedDirectory.'/../Resources/config'));
            $loader->load($file);
        }

        return $this;
    }

    /**
     * Normally, the {@see FileLocator} would be given the constant __DIR__ when
     * such configuration is defined within each explicit bundle. As {@see autoLoadService}
     * moves such functionality into this abstraction class, __DIR__ would return
     * the wrong result. Instead, we utilize the first FileResource attached to
     * the container to determine the path to the correct /Scribe[A-Z][a-z]*Bundle\.php/
     * file, allowing us to load the correct services and/or additional config files.
     *
     * @param ContainerBuilder $container
     *
     * @return string
     */
    protected function resolveBundleDirectory(ContainerBuilder $container)
    {
        $resources = $container->getResources();

        if (true === (count($resources) > 0)) {
            $bundleFilePath = (string) current($resources);

            return (string) dirname($bundleFilePath);
        }

        return (string) __DIR__;
    }

    /**
     * Based on the file extension, resolve the correct FileLoader object to
     * instantiate and return for the passed file argument.
     *
     * @param string $file
     *
     * @return XmlFileLoader|YamlFileLoader
     */
    protected function getFileLoader($file)
    {
        switch (pathinfo($file, PATHINFO_EXTENSION)) {
            case 'xml':
                return new XmlFileLoader();
            case 'yml':
            case 'yaml':
                return new YamlFileLoader();
        }

        throw RuntimeException::create()
            ->setMessage('No available service file loader for %s file with %s extension type.', $file, pathinfo($file, PATHINFO_EXTENSION));
    }

    /**
     * Process config array to container parameter key=>values.
     *
     * @param array      $configSet
     * @param string     $currentId
     * @param false|bool $parseEmptyValueSet
     *
     * @return $this
     */
    protected function processConfigsToParameters(array $configSet = [], $currentId = null, $parseEmptyValueSet = true)
    {
        if (true === (count($configSet) === 0)) {
            return $parseEmptyValueSet === true ? $this->handleConfigsToParameterWhenEmpty(
                $currentId,
                $configSet
            ) : $this;
        }

        foreach ($configSet as $id => $value) {
            $newId = $this->buildConfigParameterIndex($currentId, $id);

            if (true === is_array($value)) {
                $this->handleConfigsToParameterWhenArray($newId, $currentId, $id, $value);
            } else {
                $this->handleConfigsToParameterWhenNotArray($newId, $value);
            }
        }

        return $this;
    }

    /**
     * Set a new parameter value depending on the type of array its value is.
     *
     * @param string $prefixedId
     * @param string $noPrefixId
     * @param string $currentId
     * @param mixed  $value
     *
     * @return $this
     */
    protected function handleConfigsToParameterWhenArray($prefixedId, $noPrefixId, $currentId, $value)
    {
        if (true === (substr($currentId, -5, 5) === '_list')) {
            return $this->handleConfigsToParameterWhenArrayHash($prefixedId, $value, '_list');
        }

        if (false === ArrayInfo::isAssociative($value)) {
            return $this->handleConfigsToParameterWhenArrayInt($prefixedId, $value);
        }

        $this->processConfigsToParameters($value, $noPrefixId.$this->getIndexSeparator().$currentId);

        return $this;
    }

    /**
     * Set or update a parameter when its value is an integer array.
     *
     * @param string $id
     * @param mixed  $value
     *
     * @return $this
     */
    public function handleConfigsToParameterWhenArrayInt($id, $value)
    {
        if (true === $this->hasContainerParameter($id)) {
            $value = array_merge((array) $this->getContainerParameter($id), (array) $value);
        }

        $this->setContainerParameter($id, (array) $value);

        return $this;
    }

    /**
     * Set a new parameter within the container when it is an array hash.
     *
     * @param string $id
     * @param mixed  $value
     * @param string $search
     * @param string $replace
     *
     * @return $this
     */
    public function handleConfigsToParameterWhenArrayHash($id, $value, $search = '', $replace = '')
    {
        $this->setContainerParameter(str_replace($search, $replace, $id), $value);

        return $this;
    }

    /**
     * Set a new parameter within the container when it is a flat value (non-array).
     *
     * @param string $id
     * @param mixed  $value
     *
     * @return $this
     */
    public function handleConfigsToParameterWhenNotArray($id, $value)
    {
        $this->setContainerParameter($id, $value);

        return $this;
    }

    /**
     * Set a new parameter within the container when it contains an empty value/array/etc as a value.
     *
     * @param string|array $indexPartSet
     * @param mixed        $value
     *
     * @return $this
     */
    public function handleConfigsToParameterWhenEmpty($indexPartSet, $value)
    {
        $this->setContainerParameter(
            $this->buildConfigParameterIndex($indexPartSet),
            $this->normalizeConfigParameterValue($value)
        );

        return $this;
    }

    /**
     * Set a new parameter within the container using its completed parameter ID and respective value.
     *
     * @param string $id
     * @param mixed  $value
     *
     * @return $this
     */
    private function setContainerParameter($id, $value)
    {
        $this->getContainer()->setParameter($id, $value);

        return $this;
    }

    /**
     * Builds a final index parameter by concatenated the configured prefix, separator, and index set created.
     *
     * @param string ,... $indexPartSet
     *
     * @return string
     */
    private function buildConfigParameterIndex(...$indexPartSet)
    {
        return (string) $this->normalizeConfigParameterIndex(
            $this->getIndexPrefix().$this->indexSeparator.implode($this->indexSeparator, (array) $indexPartSet)
        );
    }

    /**
     * Normalize config index string by allowing only alphanumeric strings with periods, dashes, and underscores. Remove
     * any consecutive periods which may have been introduced while building the index. Final index MUST begin with an
     * alpha character: an exception is thrown if this is not the case.
     *
     * @param string $resolvedIndexValue
     *
     * @return string
     *
     * @throws RuntimeException
     */
    private function normalizeConfigParameterIndex($resolvedIndexValue)
    {
        $validFirstChar = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $regexDeliminator = preg_quote($this->indexSeparator, '#');

        $normalizationRegexSet = [
            '#'.$regexDeliminator.'+#' => $this->indexSeparator,
            '#[^a-z0-9'.$regexDeliminator.'_-]#i' => '',
        ];

        foreach ($normalizationRegexSet as $regex => $replace) {
            $resolvedIndexValue = (string) preg_replace($regex, $replace, $resolvedIndexValue);
        }

        if (false === stripos($validFirstChar, $resolvedIndexValue[0])) {
            throw RuntimeException::create()
                ->setMessage('DI-auto config->parameter ids must begin with a letter: the index "%s" is invalid.', $resolvedIndexValue);
        }

        return (string) $resolvedIndexValue;
    }

    /**
     * Sanitize parameter value. If value is a string, whitespace is trimmed from start and end. Otherwise, the value
     * is returned unaltered.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    private function normalizeConfigParameterValue($value)
    {
        if (is_string($value)) {
            return (string) trim($value);
        }

        return $value;
    }
}

/* EOF */
