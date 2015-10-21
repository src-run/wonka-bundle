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

        return (array) $dependencies;
    }

    /**
     * @param array $depIds
     *
     * @return bool|string
     */
    protected function resolveDependencyType(array $depIds)
    {
        if (isset($depIds['entity']) && ($value = $this->container->getParameter($depIds['entity']))) {
            return $this->resolveEntityClassToDependency($value);
        }

        if (isset($depIds['loaderClass'])) {
            return (string) $depIds['loaderClass'];
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
        if (1 !== preg_match('{(\BBundle\\\)((?:[^\s]*\\\)Entity[^\s]*\b)}', $value, $matches)) {
            return false;
        }

        $resolvedPath = str_replace($matches[0], $matches[1].'DataFixtures\\ORM', $value)
            .'\\Load'.ClassInfo::getClassName($value).'Data';

        return class_exists($resolvedPath) ? $resolvedPath : false;
    }
}

/* EOF */
