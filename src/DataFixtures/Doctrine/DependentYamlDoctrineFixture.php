<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\DataFixtures\Doctrine;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Scribe\Wonka\Utility\ClassInfo;

/**
 * DependentYamlDoctrineFixture.
 */
class DependentYamlDoctrineFixture extends YamlDoctrineFixture implements DependentFixtureInterface
{
    /**
     * @return string[]
     */
    public function getDependencies()
    {
        $dependencies = [];

        foreach ($this->m->getDependencies() as $name => $type) {
            if (!($className = $this->resolveDependencyType($type))) {
                continue;
            }

            $dependencies[] = $className;
        }

        if (count($dependencies) === 0) {
            throw new \RuntimeException(sprintf('No dependencies defined for %s.', $this->m->getName()));
        }

        return (array) $dependencies;
    }

    /**
     * @param array $depIds
     *
     * @return bool|string
     */
    protected function resolveDependencyType(array $depIds)
    {
        $ormLoader = (bool) (isset($depIds['ormLoader']) ? $depIds['ormLoader'] : false);

        if ($ormLoader === true && isset($depIds['entity'])) {
            return $this->resolveContainerParameterToLoader($depIds['entity']);
        }

        return false;
    }

    /**
     * @param string $service
     *
     * @return bool|string
     */
    protected function resolveContainerParameterToLoader($service)
    {
        if ($value = $this->container->getParameter($service)) {
            return $this->resolveEntityClassToDependency($value);
        }

        return false;
    }

    /**
     * @param string $value
     *
     * @return bool|string
     */
    protected function resolveEntityClassToDependency($value)
    {
        if (1 !== preg_match('{(Bundle\\\)((?:[^\s]*\\\)Entity[^\s]*\b)}', $value, $matches)) {
            return false;
        }

        $resolvedPath = str_replace($matches[0], $matches[1].'DataFixtures\\ORM', $value)
            .'\\Load'.ClassInfo::getClassName($value).'Data';

        return class_exists($resolvedPath) ? $resolvedPath : false;
    }
}

/* EOF */
