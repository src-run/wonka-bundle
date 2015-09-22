<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Component\HttpKernel;

use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class Kernel.
 */
class Kernel extends BaseKernel
{
    /**
     * @var array[string]
     */
    protected $enviornmentBundles = ['all' => []];

    /**
     * @return void
     */
    public function setup()
    {
    }

    /**
     * @param string        $absoluteName
     * @param array[string] $enviornmentSet
     *
     * @return $this
     */
    protected function addBundle($absoluteName, ...$enviornmentSet)
    {
        if (true === is_iterable_empty($enviornmentSet)) {
            $this->enviornmentBundles['all'][] = $absoluteName;

            return $this;
        }

        foreach ($enviornmentSet as $enviornment) {
            if (false === array_key_exists($enviornment, $this->enviornmentBundles)) {
                $this->enviornmentBundles[(string) $enviornment] = [];
            }

            if (substr($absoluteName, 0, 1) !== '\\') {
                $absoluteName = '\\' . $absoluteName;
            }

            $this->enviornmentBundles[(string) $enviornment][] = $absoluteName;
        }

        return $this;
    }

    /**
     * @return array[]
     */
    protected function resolveBundles()
    {
        if (array_key_exists($this->getEnvironment(), $this->enviornmentBundles)) {
            $bundleSetUnresolved = array_merge($this->enviornmentBundles['all'], $this->enviornmentBundles[$this->getEnvironment()]);
        } else {
            $bundleSetUnresolved = $this->enviornmentBundles['all'];
        }

        $bundleSetResolved = [];

        foreach ($bundleSetUnresolved as $bundleName) {
            $bundleSetResolved[] = new $bundleName();
        }

        return $bundleSetResolved;
    }

    /**
     * @return array
     */
    final public function registerBundles()
    {
        $this->setup();

        return $this->resolveBundles();
    }

    /**
     * @param LoaderInterface $loader
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $configurationFilePath = $this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml';

        $loader->load($configurationFilePath);
    }
}

/* EOF */
