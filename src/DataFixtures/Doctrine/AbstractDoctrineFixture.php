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

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Scribe\MantleBundle\Doctrine\Base\Entity\AbstractEntity;
use Scribe\MantleBundle\Doctrine\Exception\ORMException;
use Scribe\Wonka\Exception\ExceptionInterface;
use Scribe\Wonka\Exception\RuntimeException;
use Scribe\Wonka\Utility\Reflection\ClassReflectionAnalyser;
use Scribe\WonkaBundle\Component\DependencyInjection\Container\ContainerAwareTrait;
use Scribe\WonkaBundle\DataFixtures\Loader\YamlFixtureLoader;
use Scribe\WonkaBundle\DataFixtures\Loader\FixtureLoaderResolver;
use Scribe\WonkaBundle\DataFixtures\Locator\FixtureLocator;
use Scribe\WonkaBundle\DataFixtures\Metadata\FixtureMetadata;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * AbstractDoctrineFixture.
 */
abstract class AbstractDoctrineFixture extends AbstractFixture implements DoctrineFixtureInterface
{
    use ContainerAwareTrait;

    /**
     * @var FixtureMetadata
     */
    protected $m;

    /**
     * @var array|null
     */
    protected $dependencies = [];

    /**
     * @var bool
     */
    protected $cannibal = false;

    /**
     * @var bool
     */
    protected $refById = false;

    /**
     * @var string[]
     */
    protected $refByColumns = [];

    /**
     * @var int
     */
    protected $batch = 1000;

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @return string[]
     */
    public function getPaths()
    {
        $publicPath  = 'config/shared_public/fixtures';
        $privatePath = 'config/shared_proprietary/fixtures';

        return [
            'test-public'  => '../../../app/'.$publicPath,
            'test-private' => '../../../app/'.$privatePath,
            'self-public'  => 'app/'.$publicPath,
            'self-private' => 'app/'.$privatePath,
            'vend-public'  => $publicPath,
            'vend-private' => $privatePath,
        ];
    }

    /**
     * @return \Scribe\WonkaBundle\DataFixtures\Loader\FixtureLoaderInterface[]
     */
    public function getLoaders()
    {
        return [
            new YamlFixtureLoader(),
        ];
    }

    /**
     * @return bool
     */
    public function isLazy()
    {
        return true;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;

        $this->initializeMetadata();
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    public function getDoctrineRegistry()
    {
        return $this->container->get('doctrine');
    }

    /**
     * Parse metadata for
     */
    public function initializeMetadata()
    {
        try {

            $locator = (new FixtureLocator())
                ->setPaths($this->getPaths())
                ->setBase($this->getContainerParameter('kernel.root_dir'));

            $resolver = (new FixtureLoaderResolver())
                ->assignLoaders($this->getLoaders());

            $this->m = (new FixtureMetadata())
                ->load($this, $locator, $resolver);

        } catch (\Exception $exception) {
            throw new RuntimeException('Unable to generate metadata for fixture.', null, $exception);
        }
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        if ($this->m->isEmpty()) { return; }

        foreach ($this->m->getData() as $index => $data) {

            $entity = $this->getNewPopulatedEntity($index, $data);

            $manager->persist($entity);
            if ($this->m->isCannibal()) { $manager->flush(); }

            if ($this->m->hasReferenceByIndexEnabled()) {
                $this->addReference($this->m->getName().':'.$index, $entity);
            }

            if ($this->m->hasReferenceByColumnsEnabled()) {
                $referenceByColumnsSetConcat   = function($columns) { return implode(':', (array) $columns); };
                $referenceByColumnsSetRegister = function($columns) use ($entity, $referenceByColumnsSetConcat) {
                    $this->addReference($this->m->getName().':'.$referenceByColumnsSetConcat($columns), $entity);
                };
                array_walk($this->m->getReferenceByColumnsSets(), $referenceByColumnsSetRegister);
            }

            if (($index % $this->batch) === 0) {
                $manager->flush();
                $manager->clear();
            }
        }

        $manager->flush();
        $manager->clear();
    }

    /**
     * @param int     $index
     * @param mixed[] $values
     *
     * @return mixed
     */
    protected function getNewPopulatedEntity($index, $values)
    {
        try {
            $entity = $this->getContainerService($this->m->getServiceKey());
        } catch (\Exception $exception) {
            throw new RuntimeException('Unable to locate service id %s.', null, $exception, $this->m->getServiceKey());
        }

        try {
            return $this->hydrateEntity($entity, $index, $values);
        } catch (\Exception $exception) {
            throw new RuntimeException('Could not hydrate entity: fixture %s, index %n.', null, $exception, $this->m->getName(), $index);
        }
    }

    /**
     * @param AbstractEntity $entity
     * @param string         $index
     * @param array[]        $values
     *
     * @return mixed
     */
    protected function hydrateEntity(AbstractEntity $entity, $index, $values)
    {
        foreach ($values as $key => $val) {
            $call = $this->getHydrateEntityMethodCall($key);
            $data = $this->getHydrateEntityMethodData($key, $val);

            try {
                $this->hydrateEntityData($entity, $key, $call, $data);
            } catch(\Exception $exception) {
                $this->hydrateEntityData($entity, $key, $call, new ArrayCollection((array) $data));
            }
        }

        return $entity;
    }

    protected function hydrateEntityData(AbstractEntity $entity, $key, $call, $data)
    {
        if (method_exists($entity, $call)) {
            call_user_func([$entity, $call], $data);

            return;
        }

        try {

            $reflectProp = (new ClassReflectionAnalyser($entity))
                ->setPropertyPublic($key);

            $reflectProp->setValue($entity, $data);

        } catch (\Exception $exception) {
            throw new RuntimeException('Could not assign property %s via setter or reflection in fixture %s.', null, $exception, $key, $this->m->getName());
        }
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getHydrateEntityMethodCall($key)
    {
        return (string) sprintf('set%s', ucfirst($key));
    }

    /**
     * @param string     $key
     * @param array|null $values
     *
     * @return array|mixed
     */
    protected function getHydrateEntityMethodData($key, array $values = null)
    {
        if (!array_key_exists($key, $values)) {
            throw new RuntimeException('Could not find index %s in fixture %s.', null, null, $key, $this->m->getName());
        }

        return is_array($values[$key]) ? $this->getHydrationValueSet($values[$key]) : $this->getHydrationValue($values[$key]);
    }

    /**
     * @param array $valueSet
     *
     * @return array
     */
    protected function getHydrationValueSet(array $valueSet = [])
    {
        return (array) array_map([$this, 'getHydrationValue'], $valueSet);
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    protected function getHydrationValue($value)
    {
        if (substr($value, 0, 2) === '++') {
            return $this->getHydrationValueUsingInternalRefLookup(substr($value, 2)) ?: $value;
        }

        if (substr($value, 0, 1) === '+' && 1 === preg_match('{^\+([a-z]+:[0-9]+)$}i', $value, $matches)) {
            return $this->getHydrationValueUsingInternalRefLookup($matches[1]) ?: $value;
        }

        if (substr($value, 0, 1) === '@' && 1 === preg_match('{^@([a-z]+)\?([^=]+)=([^&]+)$}i', $value, $matches)) {
            return $this->getHydrationValueUsingSearchQuery($matches[1], $matches[2], $matches[3]) ?: $value;
        }

        return $value;
    }

    /**
     * @param string $reference
     *
     * @return mixed|null
     */
    protected function getHydrationValueUsingInternalRefLookup($reference)
    {
        return $this->getReference($reference);
    }

    /**
     * @param string $dependencyLookup
     * @param string $column
     * @param string $criteria
     *
     * @throws ORMException
     *
     * @return mixed|null
     */
    protected function getHydrationValueUsingSearchQuery($dependencyLookup, $column, $criteria)
    {
        if (!($dependency = $this->m->getDependency($dependencyLookup)) || !(isset($dependency['repository']))) {
            throw new RuntimeException('Missing dependency repo config for %s as called in fixture %s.', null, null, $dependencyLookup, $this->m->getName());
        }

        if (!$this->container->has($dependency['repository'])) {
            throw new RuntimeException('Dependency %s for fixture %s cannot be found in container.', null, null, $dependencyLookup, $this->m->getName());
        }

        $repo = $this->container->get($dependency['repository']);
        $call = isset($dependency['findMethod']) ? $dependency['findMethod'] : 'findBy'.ucwords($column);

        try {
            $result = call_user_func([$repo, $call], $criteria);
        } catch (\Exception $exception) {
            throw new ORMException('Error searching with call %s(%s) in fixture %s.', null, $exception, $call, $criteria, $this->m->getName());
        }

        if (count($result) > 0) {
            throw new ORMException('Search with call %s(%s) in fixture %s has >1 result.', null, null, $call, $criteria, $this->m->getName());
        }

        if ($result instanceof ArrayCollection) {
            return $result->first();
        }

        return is_array($result) ? array_values($result)[0] : $result;
    }
}

/* EOF */
