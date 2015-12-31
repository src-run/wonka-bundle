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
    protected $envBundles;

    public function clear()
    {
        $this->envBundles = ['all' => []];

        return $this;
    }

    /**
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
    protected function addBundle($absoluteName, ...$envSet)
    {
        if (true === isEmptyIterable($envSet)) {
            $this->envBundles['all'][] = $absoluteName;

            return $this;
        }

        foreach ($envSet as $env) {
            if (false === array_key_exists($env, $this->envBundles)) {
                $this->envBundles[(string) $env] = [];
            }

            if (substr($absoluteName, 0, 1) !== '\\') {
                $absoluteName = '\\'.$absoluteName;
            }

            $this->envBundles[(string) $env][] = $absoluteName;
        }

        return $this;
    }

    /**
     * @return array[]
     */
    protected function resolveBundles()
    {
        $bundleSetUnresolved = array_unique($this->envBundles['all']);

        if (array_key_exists($this->getEnvironment(), $this->envBundles) && $this->getEnvironment() !== 'prod') {
            $bundleSetUnresolved = array_unique(array_merge($bundleSetUnresolved, $this->envBundles[$this->getEnvironment()]));
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
        $this->clear()->setup();

        return $this->resolveBundles();
    }

    /**
     * @param LoaderInterface $loader
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $configurationFilePath = $this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml';

        $loader->load($configurationFilePath);
    }
}

/* EOF */
