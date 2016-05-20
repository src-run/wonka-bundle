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

namespace SR\WonkaBundle\Component\DependencyInjection\Compiler\Pass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class AbstractCompilerPass.
 */
abstract class AbstractCompilerPass implements CompilerPassInterface
{
    /**
     * Return the name of the service that handles the collection of tagged items found (the chain manager).
     *
     * @return string
     */
    abstract public function getRegistrarService();

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
        return 'addAttendant';
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return $this
     */
    public function process(ContainerBuilder $container)
    {
        $serviceSearchTag = $this->getAttendantTagName();
        $registrarService = $this->getRegistrarService();

        if (!$container->hasDefinition($registrarService)) {
            return $this;
        }

        $registrar = $container->getDefinition($registrarService);
        $foundAttendants = $container->findTaggedServiceIds($serviceSearchTag);

        if (sizeof($foundAttendants) === 0) {
            return $this;
        }

        foreach ($foundAttendants as $a => $properties) {
            $this->registerTaggedService($registrar, $a, $properties);
        }

        return $this;
    }

    /**
     * @param Definition $registrar
     * @param string     $attendantService
     * @param array|null $attendantProperties
     *
     * @return $this
     */
    final protected function registerTaggedService(Definition $registrar, $attendantService, array $attendantProperties = [])
    {
        $parameters = [new Reference($attendantService)];

        foreach ($attendantProperties as $attributes) {
            $registrar->addMethodCall(
                $this->getRegistrarAddAttendantMethodName(),
                $this->buildServiceParameters($parameters, $attributes)
            );
        }

        return $this;
    }

    /**
     * @param array $parameters
     * @param array $attributes
     *
     * @return array
     */
    final protected function buildServiceParameters(array $parameters, array $attributes)
    {
        return (array) array_merge(
            $parameters,
            $this->buildServiceParameterPriority($attributes),
            $this->buildServiceParameterExtra($attributes)
        );
    }

    /**
     * @param array $attributes
     *
     * @return array[]
     */
    final protected function buildServiceParameterPriority(array $attributes)
    {
        if (false === array_key_exists('priority', $attributes)) {
            return [];
        }

        return ['priority' => $attributes['priority']];
    }

    /**
     * @param array $attributes
     *
     * @return array[]
     */
    final protected function buildServiceParameterExtra(array $attributes)
    {
        if (true === array_key_exists('priority', $attributes)) {
            unset($attributes['priority']);
        }

        return count($attributes) === 0 ? [] : ['extra' => $attributes];
    }
}

/* EOF */
