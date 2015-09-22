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
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\ORMException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use Scribe\WonkaBundle\Component\DependencyInjection\Container\ContainerAwareInterface;
use Scribe\WonkaBundle\Component\DependencyInjection\Container\ContainerAwareTrait;
use Scribe\Wonka\Exception\ExceptionInterface;
use Scribe\Wonka\Exception\RuntimeException;
use Scribe\Wonka\Utility\ClassInfo;

/**
 * AbstractDoctrineYamlFixture.
 */
abstract class AbstractDoctrineYamlFixture extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Operational mode: truncate table.
     *
     * @var int
     */
    const MODE_TRUNCATE = -1;

    /**
     * Operational mode: merge entries into table.
     *
     * @var int
     */
    const MODE_MERGE = 0;

    /**
     * Operational mode: append to table.
     *
     * @var int
     */
    const MODE_APPEND = 1;

    /**
     * Default operational mode: truncate.
     *
     * @var int
     */
    const MODE_DEFAULT = self::MODE_TRUNCATE;

    /**
     * @var array
     */
    protected $fixture = [];

    /**
     * @var null|string
     */
    protected $name = null;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $orm = [];

    /**
     * @var int
     */
    protected $priority = 0;

    /**
     * @var array|null
     */
    protected $dependencies = [];

    /**
     * @var bool
     */
    protected $cannibal = false;

    /**
     * @var string
     */
    protected $mode = self::MODE_DEFAULT;

    /**
     * @var array|false
     */
    protected $references;

    /**
     * @var bool
     */
    protected $referencesById;

    /**
     * @var array
     */
    protected $referencesByColumns;

    /**
     * @var int
     */
    protected $batchSize = 1000;

    /**
     * @var array
     */
    protected static $fixtureDirPaths = [
        'public'            => '/../../../app/config/shared_public/fixtures/',
        'proprietary'       => '/../../../app/config/shared_proprietary/fixtures/',
        'selfPublic'        => 'app/config/shared_public/fixtures/',
        'selfProprietary'   => 'app/config/shared_proprietary/fixtures/',
        'vendorPublic'      => '/config/shared_public/fixtures/',
        'vendorProprietary' => '/config/shared_proprietary/fixtures/',
    ];

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->init();
    }

    /**
     * Init fixture.
     */
    public function init()
    {
        $this
            ->setOrmFixtureName($this->getFixtureNameFromClassName())
            ->loadOrmFixtureData();
    }

    /**
     * @throws RuntimeException
     *
     * @return mixed
     */
    protected function getFixtureNameFromClassName()
    {
        $class  = ClassInfo::getClassNameByInstance($this);
        $return = preg_match('#^Load(.*?)Data$#', $class, $matches);

        if (false !== $return && 0 !== $return && false !== isset($matches[1])) {
            return $matches[1];
        }

        throw new RuntimeException('Could not determine the fixture name from the class loader name "%s".'.
            RuntimeException::CODE_FIXTURE_DATA_INCONSISTENT, null, $class);
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    protected function setOrmFixtureName($name)
    {
        $this->name = (string) $name;

        return $this;
    }

    /**
     * Forces flush on each iteration.
     *
     * @param bool $cannibal
     */
    protected function setOrmFixtureAsCannibal($cannibal = true)
    {
        $this->cannibal = (bool) $cannibal;
    }

    /**
     * @return array
     */
    protected function getFixtures()
    {
        return $this->data;
    }

    /**
     * @param int $priority
     *
     * @return $this
     */
    protected function setOrmFixturePriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @param object $entity
     * @param mixed  $f
     *
     * @return object
     */
    protected function setNewFixtureDataForEntity($entity, $f, $i)
    {
        foreach ($f as $name => $value) {
            $setter = 'set'.ucfirst($name);
            $data = $this->getFixtureDataValue($name, $f);

            try {
                $entity->$setter($data);
            } catch (ContextErrorException $e) {
                try {
                    $dataCollection = new ArrayCollection((array)$data);
                    $entity->$setter($dataCollection);
                } catch (\Exception $ei) {
                    throw new RuntimeException('Unrecoverable error for fixture "%s", entity "%s", id "%s", and setter "%s".',
                        RuntimeException::CODE_FIXTURE_DATA_INCONSISTENT, null, $this->name, get_called_class(), (string) $i, $setter);
                }
            }
        }

        return $entity;
    }

    /**
     * @param string $i
     * @param array  $f
     *
     * @return array
     */
    protected function getFixtureDataValue($i, array $f = null)
    {
        if (false === is_array($f) || false === array_key_exists($i, $f)) {
            throw new RuntimeException('Could not find data "%s" in fixtures array for "%s".',
                RuntimeException::CODE_FIXTURE_DATA_INCONSISTENT, null, $i, $this->name);
        }

        return (is_array($f[$i]) ? $this->getFixtureValueAsArrayWithRefs($f[$i]) : $this->getFixtureValueWithRefs($f[$i]));
    }

    /**
     * @param array $valueCollection
     *
     * @return array
     */
    protected function getFixtureValueAsArrayWithRefs(array $valueCollection = [])
    {
        foreach ($valueCollection as &$value) {
            $value = $this->getFixtureValueWithRefs($value);
        }

        return $valueCollection;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    protected function getFixtureValueWithRefs($value)
    {
        $return = null;
        $matches = null;
        if (0 !== preg_match('#^@([A-Za-z]*)\?([^=]*)=([^&]*)$#', $value, $matches)) {
            $return = $this->getFixtureValueBySearchRef($matches);
        }

        $matches = null;
        if ($return === null && 0 !== preg_match('#^\+([a-zA-Z]*:[0-9]+)$#', $value, $matches)) {
            $return = $this->getFixtureValueByInternalRef($matches);
        }

        $matches = null;
        if ($return === null && 0 !== preg_match('#^\+\+(.*)$#', $value, $matches)) {
            $return = $this->getFixtureValueByInternalCustomRef($matches);
        }

        return ($return !== null ? $return : $value);
    }

    /**
     * @param array $matches
     *
     * @return null|object
     */
    protected function getFixtureValueByInternalCustomRef($matches)
    {
        if (count($matches) !== 2) { return null; }

        return $this->getReference($matches[1]);
    }

    /**
     * @param array $matches
     *
     * @return null|object
     */
    protected function getFixtureValueByInternalRef($matches)
    {
        if (count($matches) !== 2) { return null; }

        return $this->getReference($matches[1]);
    }

    /**
     * @param array $matches
     *
     * @return null|object
     */
    protected function getFixtureValueBySearchRef($matches)
    {
        if (count($matches) !== 4) { return null; }

        $dependencyName = $matches[1];
        $dependencyColumn = $matches[2];
        $dependencySearch = $matches[3];

        if (false === array_key_exists($dependencyName, $this->dependencies)) {
            throw new RuntimeException('You must specify dependency values for repository and entity services in your YAML file for %s.',
                RuntimeException::CODE_MISSING_ARGS, null, $dependencyName);
        }

        $dependency = $this->dependencies[$dependencyName];

        if (false === array_key_exists('repository', $dependency)) {
            throw new RuntimeException('You must specify dependency repo service in your YAML file for %s.',
                null, null, $dependency['repository']);
        } elseif (false === $this->container->has($dependency['repository'])) {
            throw new RuntimeException('The dependency repo service "%s" cannot be found in the container.',
                null, null, $dependency['repository']);
        }

        $dependencyRepository = $this->container->get($dependency['repository']);

        if (true === array_key_exists('findMethod', $dependency)) {
            $repositoryMethod = $dependency['findMethod'];
        } else {
            $repositoryMethod = 'findBy' . ucwords($dependencyColumn);
        }

        try {
            $searchResult = $dependencyRepository->$repositoryMethod($dependencySearch);
        } catch (ORMException $e) {
            throw new RuntimeException('The requested dependency search "%s(%s)" threw an exception: %s.', ExceptionInterface::CODE_GENERIC_FROM_BUNDLE,
                $e, $repositoryMethod, $dependencySearch, $e->getMessage());
        }

        if (($searchResult instanceof ArrayCollection && $searchResult->count() > 1) ||
            (is_array($searchResult) && count($searchResult) > 1))
        {
            throw new RuntimeException('The requested search reference returned more than one result: %s, %s, %s', null, null,
                (string) $dependencyName, (string) $dependencyColumn, (string) $dependencySearch
            );
        }

        if ($searchResult instanceof ArrayCollection) {
            return $searchResult->first();
        } elseif (is_array($searchResult)) {
            return $searchResult[0];
        }

        return $searchResult;
    }

    /**
     * @return object
     */
    protected function getNewFixtureEntity()
    {
        $e = null;
        $n = $this->name;

        if (array_key_exists('entity', $this->orm)) {
            $n = $this
                ->container
                ->getParameter($this->orm['entity']);
        }

        $e = new $n();

        return $e;
    }

    /**
     * @param string $name
     *
     * @return string[]|null[]
     */
    protected function fixtureDataDirectoryResolver($name)
    {
        $fixtureBasePath = null;
        $fixtureYamlPath = null;

        $kernelRoot = $this
            ->container
            ->get('kernel')
            ->getRootDir();

        foreach (static::$fixtureDirPaths as $fixtureName => $fixtureDirPath) {
            $fixtureDirPath = $kernelRoot.DIRECTORY_SEPARATOR.$fixtureDirPath;

            if (true === file_exists($fixtureDirPath)) {
                $fixtureBasePath = $fixtureDirPath;
                $fixtureYamlPath = $fixtureDirPath.DIRECTORY_SEPARATOR.$this->buildYamlFileName($name);
                if (file_exists($fixtureYamlPath)) {
                    break;
                }
            }
        }

        return $this->validateFixtureDataResolvedPaths(
            $name,
            $fixtureBasePath,
            $fixtureYamlPath
        );
    }

    /**
     * @param string      $name
     * @param string|null $basePath
     * @param string|null $yamlPath
     *
     * @return string[]
     */
    protected function validateFixtureDataResolvedPaths($name, $basePath, $yamlPath)
    {
        if (null === $basePath || null === $yamlPath ||
            false === ($basePath = realpath($basePath)) ||
            false === ($yamlPath = realpath($yamlPath))) {
            throw new RuntimeException('Could not find YAML fixture for %s in known paths: [%s].',
                null, null, (string) $name, (string) implode(',', static::$fixtureDirPaths));
        }

        return [
            $basePath,
            $yamlPath,
        ];
    }

    /**
     * @return $this
     *
     * @throws RuntimeException
     */
    public function loadOrmFixtureData()
    {
        if (null === ($name = $this->name)) {
            throw new RuntimeException('You must provide a fixture name.');
        }

        list(, $fixtureYamlPath) = $this->fixtureDataDirectoryResolver($name);

        $yaml = new Parser();

        try {
            $fixture = $yaml->parse(@file_get_contents($fixtureYamlPath));
        } catch (ParseException $e) {
            throw new RuntimeException('Unable to parse the YAML string: %s', ExceptionInterface::CODE_GENERIC_FROM_BUNDLE, $e, $e->getMessage());
        }

        if (false === isset($fixture[$name])) {
            throw new RuntimeException('Unable to parse the YAML root %s in file %s.', null, null, $name, $fixtureYamlPath);
        } else {
            $fixtureRoot = $fixture[$name];
        }

        if (false === isset($fixtureRoot['orm'])) {
            throw new RuntimeException('Unable to find required fixture section %s in file %s.', null, null, 'orm', $fixtureYamlPath);
        }

        if (false === isset($fixtureRoot['data']) && null !== $fixtureRoot['data']) {
            throw new RuntimeException('Unable to find required fixture section %s in file %s.', null, null, 'data', $fixtureYamlPath);
        }

        if (false === array_key_exists('dependencies', $fixtureRoot)) {
            throw new RuntimeException('Unable to find required fixture section %s in file %s.', null, null, 'dependencies', $fixtureYamlPath);
        }

        $this->fixture = $fixtureRoot;
        $this->orm = $fixtureRoot['orm'];
        $this->data = $fixtureRoot['data'];
        $this->priority = (array_key_exists('priority', $this->orm) ? $this->orm['priority'] : 0);
        $this->cannibal = (bool) (array_key_exists('cannibal', $this->orm) ? $this->orm['cannibal'] : false);
        $this->mode = (int) $this->determineMode();
        $this->dependencies = $fixtureRoot['dependencies'];
        $this->references = (array_key_exists('references', $fixtureRoot) ? $fixtureRoot['references'] : []);
        $this->referencesById = (bool) (array_key_exists('usingId', $this->references) ? $this->references['usingId'] : false);
        $this->referencesByColumns = (array) (array_key_exists('usingColumns', $this->references) ? $this->references['usingColumns'] : []);

        return $this;
    }

    /**
     * @return int|mixed
     */
    protected function determineMode()
    {
        if (false === array_key_exists('mode', $this->orm) ||
            false === ($mode = $this->orm['mode'])) {
            return self::MODE_DEFAULT;
        }

        $modeRequest = 'self::MODE_'.strtoupper($mode);

        if (true !== defined($modeRequest)) {
            return self::MODE_DEFAULT;
        }

        return constant($modeRequest);
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        if (true !== is_array($this->data)) {
            return;
        }

        echo "Running";

        foreach ($this->data as $i => $f) {
            $entity = $this->getNewFixtureEntity();
            $entity = $this->setNewFixtureDataForEntity($entity, $f, $i);

            $manager->persist($entity);

            if ($this->referencesById === true) {
                $this->addReference($this->name.':'.$i, $entity);
            }

            if (count($this->referencesByColumns) > 0) {
                $referenceString = $this->name;
                foreach ($this->referencesByColumns as $referenceColumn) {
                    $referenceString .= ':'.$this->data[$i][$referenceColumn];
                }
                $this->addReference($referenceString, $entity);
            }

            if ($this->cannibal === true || ($i % $this->batchSize) === 0) {
                echo ".";
                $manager->flush();
                $manager->clear();
            }
        }

        echo "done!" . PHP_EOL;

        $manager->flush();
        $manager->clear();
    }

    /**
     * @param string $fixtureName
     *
     * @return string
     */
    public function buildYamlFileName($fixtureName)
    {
        return $fixtureName.'Data.yml';
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return (int) ($this->priority);
    }
}

/* EOF */
