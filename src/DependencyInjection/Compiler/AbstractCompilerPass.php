<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * Class AbstractCompilerPass.
 */
abstract class AbstractCompilerPass implements CompilerPassInterface
{
    /**
     * @var Psr/Logger
     */
    protected $logger;

    /**
     * @var null|string
     */
    protected $chainAddMethodName;

    /**
     * Return the name of the service that handles the collection of tagged items found (the chain manager).
     *
     * @return string
     */
    abstract protected function getChainSrvName();

    /**
     * Return the name of the search tag to find services to be attached to the chain (the chain subscribers).
     *
     * @return string
     */
    abstract protected function getChainTagName();

    /**
     * @return string
     */
    protected function getChainAddMethodName()
    {
        $addMethodName = $this->chainAddMethodName;

        return (string) null === $addMethodName ? self::CHAIN_ADD_METHOD_NAME : $addMethodName;
    }

    /**
     * @param string $methodName
     *
     * @return $this
     */
    protected function setChainAddMethodName($methodName)
    {
        $this->chainAddMethodName = (string) $methodName;

        return $this;
    }

    /**
     * @param ContainerBuilder $container
     * @param false|bool       $debug
     *
     * @return $this
     */
    public function compile(ContainerBuilder $container, $debug = false)
    {
        $this->registerLogger($container, $debug);

        $chainSrvName = $this->getChainSrvName();
        $chainTagName = $this->getChainTagName();

        if (false === $container->hasDefinition($chainSrvName)) {
            return $this;
        }

        $chainSrvDef = $container->getDefinition($chainSrvName);
        $chainTagSet = $container->findTaggedServiceIds($chainTagName);

        if (is_iterable_empty($chainTagSet)) {
            return $this;
        }

        foreach ($chainTagSet as $taggedSrvId => $taggedSrvAttributes) {
            $this->registerTaggedService($chainSrvDef, $taggedSrvId, $taggedSrvAttributes);
        }

        return $this;
    }

    /**
     * @param Definition $chainDefinition
     * @param string     $serviceId
     * @param array|null $serviceAttributes
     *
     * @return $this
     */
    final protected function registerTaggedService(Definition $chainDefinition, $serviceId, array $serviceAttributes = [])
    {
        $parameters = [new Reference($serviceId)];

        foreach ($serviceAttributes as $attributeSet) {
            $chainDefinition->addMethodCall(
                $this->getChainAddMethodName(),
                $this->buildServiceParameters($parameters, $attributeSet)
            );

            $this->log('Registered "%s" service for tag "%s" to "%s"', $serviceId, $this->getChainTagName, $this->getChainSrvName);
        }

        return $this;
    }

    /**
     * @param array $parameters
     * @param array $attributeSet
     *
     * @return array
     */
    final protected function buildServiceParameters(array $parameterSet, array $attributeSet)
    {
        return (array) array_merge(
            $parameterSet,
            $this->buildServiceParameterPriority($attributeSet),
            $this->buildServiceParameterExtra($attributeSet)
        );
    }

    /**
     * @param array $attributeSet
     *
     * @return array[]
     */
    final protected function buildServiceParameterPriority(array $attributeSet)
    {
        if (false === array_key_exists('priority', $attributeSet)) {
            return (array) [];
        }

        return (array) ['priority' => $attributeSet['priority']];
    }

    /**
     * @param array $attributeSet
     *
     * @return array[]
     */
    final protected function buildServiceParameterExtra(array $attributeSet)
    {
        if (true === array_key_exists('priority', $attributeSet)) {
            unset($attributeSet['priority']);
        }

        if (is_iterable_empty($attributeSet)) {
            return (array) [];
        }

        return (array) ['extra' => $attributeSet];
    }

    /**
     * @param ContainerBuilder $container
     * @param false|bool       $debug
     *
     * @return $this
     */
    final protected function registerLogger(ContainerBuilder $container, $debug = false)
    {
        if (true === $debug && true === $container->hasDefinition('logger')) {
            $this->logger = $container->getDefinition('logger');
        }

        return $this;
    }

    /**
     * @param string     $message
     * @param string,... $replacementSet
     *
     * @return $this
     */
    final protected function log($message, ...$replacementSet)
    {
        if (false === ($this->logger instanceof LoggerInterface)) {
            return $this;
        }

        if (is_array_not_empty($replacementSet)) {
            $message = sprintf($message, ...$replacementSet);
        }

        $this->logger->debug(__CLASS__ .' - ' . $message);

        return $this;
    }
}

/* EOF */
