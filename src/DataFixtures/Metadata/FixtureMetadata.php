<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\DataFixtures\Metadata;

use Scribe\Wonka\Exception\RuntimeException;
use Scribe\Wonka\Utility\ClassInfo;
use Scribe\WonkaBundle\DataFixtures\Doctrine\DoctrineFixtureInterface;
use Scribe\WonkaBundle\DataFixtures\Loader\FixtureLoaderResolverInterface;
use Scribe\WonkaBundle\DataFixtures\Locator\FixtureLocatorInterface;
use Scribe\WonkaBundle\DataFixtures\Tree\TreeStore;

/**
 * FixtureMetadata.
 */
class FixtureMetadata
{
    /**
     * @var DoctrineFixtureInterface
     */
    protected $handler;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var string
     */
    protected $contents;

    /**
     * @var TreeStore
     */
    protected $tree;

    /**
     * @param DoctrineFixtureInterface       $handler
     * @param FixtureLocatorInterface        $locator
     * @param FixtureLoaderResolverInterface $loaderResolver
     *
     * @return $this
     */
    public function load(DoctrineFixtureInterface $handler, FixtureLocatorInterface $locator, FixtureLoaderResolverInterface $loaderResolver)
    {
        $this->className = ClassInfo::getClassNameByInstance($handler);
        $this->handler   = $handler;
        $this->type      = $handler->getType();
        $this->name      = $this->resolveName();
        $this->fileName  = $this->resolveFileName();
        $this->filePath  = $this->resolveLocation($locator);
        $this->contents  = $this->resolveContents($loaderResolver);
        $this->tree      = new TreeStore($this->contents, $this->name);

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return (int) ($this->tree->get('orm', 'priority') ?: 0);
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return (array) $this->tree->get('dependencies');
    }

    /**
     * @return array
     */
    public function getData()
    {
        return (array) $this->tree->get('data');
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return (bool) empty($this->getData());
    }

    /**
     * @return bool
     */
    public function isCannibal()
    {
        return (bool) $this->tree->get('orm', 'cannibal');
    }

    public function hasRequestedRefById()
    {

    }

    /**
     * @return null|string
     */
    public function getServiceKey()
    {
        return $this->tree->get('orm', 'entity');
    }

    /**
     * @return bool
     */
    public function hasReferenceByIndexEnabled()
    {
        return (bool) ($this->tree->get('references', 'index') || $this->tree->get('references', 'usingId'));
    }

    /**
     * @return bool
     */
    public function hasReferenceByColumnsEnabled()
    {
        return (bool) ($this->tree->get('references', 'columns') || $this->tree->get('references', 'usingColumns'));
    }

    /**
     * @return array
     */
    public function getReferenceByColumnsSets()
    {
        $columnSets = [];
        $prepareColumnSets = function(&$set) { $set = (array) $set; };

        if (null !== ($columnSets = $this->tree->get('references', 'columns'))) {
            array_walk($columnSets, $prepareColumnSets);
        } else if (null !== ($columnSets = $this->tree->get('references', 'usingColumns'))) {
            array_walk($columnSets, $prepareColumnSets);
        }

        return (array) $columnSets;
    }

    /**
     * @param string $name
     *
     * @return null|mixed
     */
    public function getDependency($name)
    {
        return $this->tree->get('dependencies', $name);
    }

    /**
     * @return string
     */
    protected function resolveName()
    {
        if (1 !== preg_match('{\bLoad([a-zA-Z]{1,})Data}', $this->className, $matches)) {
            throw new RuntimeException('Unable to resolve fixture name for %s.', null, null, $className);
        }

        return (string) $matches[1];
    }

    /**
     * @return string
     */
    protected function resolveFileName()
    {
        return (string) $this->name.'Data.'.$this->type;
    }

    /**
     * @param FixtureLocatorInterface $locator
     *
     * @return string
     */
    protected function resolveLocation(FixtureLocatorInterface $locator)
    {
        $l = $locator->locate($this->fileName);

        return (string) array_first($l);
    }

    /**
     * @param FixtureLoaderResolverInterface $loaderResolver
     *
     * @return false|\Symfony\Component\Config\Loader\LoaderInterface
     */
    protected function resolveContents(FixtureLoaderResolverInterface $loaderResolver)
    {
        if (false === ($loader = $loaderResolver->resolve($this->filePath, $this->type))) {
            throw new RuntimeException('Unable to resolve appropriate loader for %s:%s.', null, null, $this->filePath, $this->type);
        }

        return $loader->load($this->filePath, $this->type);
    }
}

/* EOF */
