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

namespace SR\WonkaBundle\Component\HttpKernel;

use SR\Exception\InvalidArgumentException;
use SR\WonkaBundle\Component\HttpKernel\Bundle\InstanceDefinition;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class Kernel.
 */
class Kernel extends \Symfony\Component\HttpKernel\Kernel
{
    /**
     * @var InstanceDefinition[]
     */
    protected $bundleDefinitions = [];

    /**
     * @return $this
     */
    public function clearRegistrations()
    {
        $this->bundleDefinitions = [];

        return $this;
    }

    /**
     * @param string   $fqcn
     * @param string[] $environments
     *
     * @return InstanceDefinition
     */
    final protected function register($fqcn)
    {
        $this->bundleDefinitions[] = $definition = InstanceDefinition::create($fqcn);

        return $definition;
    }

    /**
     * @param string $environment
     *
     * @return InstanceDefinition[]
     */
    final protected function filterBundles($environment)
    {
        return array_filter($this->bundleDefinitions, function (InstanceDefinition $definition) use ($environment) {
            return $definition->hasEnvironment($environment);
        });
    }

    /**
     * @return object[]
     */
    final public function registerBundles()
    {
        return array_map(function (InstanceDefinition $definition) {
            return $definition->getInstance();
        }, $this->filterBundles($this->getEnvironment()));
    }

    /**
     * @param LoaderInterface $loader
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}

/* EOF */
