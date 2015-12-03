<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Utility\TestCase;

use PDO;
use PDOException;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use Phactory\Sql\Phactory;

/**
 * Class PhactoryTestCase.
 */
abstract class PhactoryTestCase extends EntityTestCase
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var \Phactory\Sql\Phactory
     */
    protected $factory;

    /**
     * YAML configuration data for test fixtures.
     *
     * @var Parser
     */
    protected $config;

    /**
     * array for storing all Phactory objects as they are generated.
     *
     * @var array
     */
    private $sampleData = [];

    /**
     * handle constructing the object instance.
     */
    public function setUp()
    {
        parent::setUp();

        $this
            ->setupPDO()
            ->setupPhactory()
            ->setupFixtureData()
            ->setupFactoryDefaults();
    }

    /**
     * @return $this
     */
    private function setupPDO()
    {
        try {
            $dbHost = $this->container->getParameter('database_host');
            $dbName = $this->container->getParameter('database_name');
            $dbUser = $this->container->getParameter('database_user');
            $dbPass = $this->container->getParameter('database_password');
        } catch (\InvalidArgumentException $e) {
            die('Could not obtain the DB connection parameters from the Symfony container: '.$e->getMessage());
        }

        try {
            $this->pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
        } catch (PDOException $e) {
            die('Could not connect to the DB: '.$e->getMessage());
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function setupPhactory()
    {
        $this->factory = new Phactory($this->pdo);

        return $this;
    }

    /**
     * Gets the value of fixtureLocation.
     *
     * @return string
     */
    public function getFixtureLocation()
    {
        return $this->container->getParameter('kernel.root_dir').'/../fixtures/data.yml';
    }

    /**
     * @return $this
     */
    private function setupFixtureData()
    {
        $yamlPath = $this->getFixtureLocation();
        $yaml = new Parser();

        try {
            $this->config = $yaml->parse(file_get_contents($yamlPath));
        } catch (ParseException $e) {
            printf('Unable to parse the YAML string: %s', $e->getMessage());
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function setupFactoryDefaults()
    {
        foreach ($this->config as $type => $info) {
            $this->factory->setInflection($type, $info['table']);
            $data = $this->setDatetimeValues($info['data']);

            if (array_key_exists('assocs', $info)) {
                $tableAssocs = [];

                foreach ($info['assocs'] as $name => $field) {
                    $tableAssocs[$name] = $this->factory->manyToOne($name, $field);
                };

                $this->factory->define($type, $data, $tableAssocs);
            } else {
                $this->factory->define($type, $data);
            }
        }

        return $this;
    }

    protected function setDatetimeValues($data)
    {
        foreach ($data as $key => $value) {
            if ($value === 'NOW') {
                $data[$key] = date('Y-m-d H:i:s');
            }
        }

        return $data;
    }

    public function recordSampleData($type, $row)
    {
        if (array_key_exists($type, $this->sampleData)) {
            array_push($this->sampleData[$type], $row);
        } else {
            $this->sampleData[$type] = [$row];
        }
    }

    public function createRowCountTimes($table, $count = 1)
    {
        for ($i = 1; $i <= $count; ++$i) {
            $row = $this->factory->create($table);
            $this->recordSampleData($table, $row);
        }
    }

    public function createRowWithAssociationsCountTimes($table, $count = 1, $existing = false)
    {
        $assocs = [];
        foreach ($this->config[$table]['assocs'] as $assoc => $field) {
            if ($existing && array_key_exists($assoc, $this->sampleData)) {
                $assocs[$assoc] = $this->sampleData[$assoc][0];
            } else {
                $obj = $this->factory->create($assoc);
                $assocs[$assoc] = $obj;
                $this->recordSampleData($table, $obj);
            }
        }

        for ($i = 1; $i <= $count; ++$i) {
            $row = $this->factory->createWithAssociations($table, $assocs);
            $this->recordSampleData($table, $row);
        }
    }

    public function isBasicMaker($method, $arguments)
    {
        if (substr($method, 0, 4) == 'make' && substr($method, -16) != 'WithAssociations' && count($arguments) == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function isAssociationMaker($method, $arguments)
    {
        if (substr($method, 0, 4) == 'make' && substr($method, -16) == 'WithAssociations' && count($arguments) <= 2) {
            return true;
        } else {
            return false;
        }
    }

    public function __call($method, $arguments)
    {
        if ($this->isAssociationMaker($method, $arguments)) {
            $type = lcfirst(substr($method, 4, -17));

            if (count($arguments) == 1) {
                array_push($arguments, false);
            }

            $this->createRowWithAssociationsCountTimes($type, $arguments[0], $arguments[1]);
        } elseif ($this->isBasicMaker($method, $arguments)) {
            $type = lcfirst(substr($method, 4, -1));
            $this->createRowCountTimes($type, $arguments[0]);
        } elseif (array_key_exists(substr($method, 0, -4), $this->config) and substr($method, -4) == 'Rows') {
            $type = substr($method, 0, -4);

            return $this
                ->container
                ->get($this->config[$type]['service'])
                ->findAll()
                ;
        } else {
            throw new \Exception("{$method} is not an available method in ".get_class($this));
        }
    }

    public function assertCanSetGetProperty($entity, $property, $newVal = 'foocatchoo')
    {
        $capsProperty = ucfirst($property);
        $getter = 'get'.$capsProperty;
        $this->assertTrue(is_string($entity->{$getter}()), "{$getter} did not return a string.");
        $setter = 'set'.$capsProperty;
        $entity->{$setter}($newVal);
        $this->assertSame($entity->{$getter}(), $newVal, "{$getter} did not return expected value of {$newVal}.");
    }

    public function assertHasAndCanClearEntity($entity, $property)
    {
        $capsProperty = ucfirst($property);
        $checker = 'has'.$capsProperty;
        $basicClearer = 'clear'.$capsProperty;
        $clearer = (method_exists($entity, $basicClearer)) ? $basicClearer : 'unset'.$capsProperty;
        $this->assertTrue($entity->{$checker}(), "{$checker} returned false.");
        $entity->{$clearer}();
        $this->assertTrue(!$entity->{$checker}(), "{$clearer} did not nullify property.");
    }

    // checks that a given entity can set its updated_on and modified_on properties
    public function assertCanGetSetTimes($entity)
    {
        $time = new \Datetime();
        $entity->setCreatedOn($time);
        $this->assertEquals($entity->getCreatedOn(), $time);
        $entity->setUpdatedOn($time);
        $this->assertEquals($entity->getUpdatedOn(), $time);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this
            ->factory
            ->recall()
        ;

        $this->pdo = null;

        parent::tearDown();
    }
}
