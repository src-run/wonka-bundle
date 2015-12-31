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

namespace Scribe\WonkaBundle\Component\DependencyInjection\Compiler\Pass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class AbstractCompilerPass.
 */
abstract class AbstractCompilerPass implements CompilerPassInterface
{
    /**
     * @var null|string
     */
    protected $registrarAddAttendantMethodName;

    /**
     * Return the name of the service that handles the collection of tagged items found (the chain manager).
     *
     * @return string
     */
    abstract public function getRegistrarSrvName();

    /**
     * Return the name of the search tag to find services to be attached to the chain (the chain subscribers).
     *
     * @return string
     */
    abstract public function getAttendantTagName();

    /**
     * @return string
     */
    public function getRegistrarAddAttendantMethodName()
    {
        if (isNullOrEmpty($this->registrarAddAttendantMethodName)) {
            return self::REGISTRAR_ADD_ATTENDANT_METHOD_NAME;
        }

        return $this->registrarAddAttendantMethodName;
    }

    /**
     * @param string $methodName
     *
     * @return $this
     */
    public function setRegistrarAddAttendantMethodName($methodName)
    {
        $this->registrarAddAttendantMethodName = (string) $methodName;

        return $this;
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return $this
     */
    public function process(ContainerBuilder $container)
    {
        $registrarSrvName = $this->getRegistrarSrvName();
        $attendantTagName = $this->getAttendantTagName();

        if (false === $container->hasDefinition($registrarSrvName)) {
            return $this;
        }

        $registrarSrvDef = $container->getDefinition($registrarSrvName);
        $attendantSrvSet = $container->findTaggedServiceIds($attendantTagName);

        if (isEmptyIterable($attendantSrvSet)) {
            return $this;
        }

        foreach ($attendantSrvSet as $taggedSrvIdent => $taggedSrvProps) {
            $this->registerTaggedService($registrarSrvDef, $taggedSrvIdent, $taggedSrvProps);
        }

        return $this;
    }

    /**
     * @param Definition $registrarSrvDef
     * @param string     $attendantSrvIdent
     * @param array|null $attendantSrvProps
     *
     * @return $this
     */
    final protected function registerTaggedService(Definition $registrarSrvDef, $attendantSrvIdent, array $attendantSrvProps = [])
    {
        $parameters = [new Reference($attendantSrvIdent)];

        foreach ($attendantSrvProps as $attributeSet) {
            $registrarSrvDef->addMethodCall(
                $this->getRegistrarAddAttendantMethodName(),
                $this->buildServiceParameters($parameters, $attributeSet)
            );
        }

        return $this;
    }

    /**
     * @param array $parameterSet
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

        if (isEmptyIterable($attributeSet)) {
            return (array) [];
        }

        return (array) ['extra' => $attributeSet];
    }
}

/* EOF */
