<?php

/*
 * This file is part of the `src-run/wonka-bundle` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\WonkaBundle\Component\HttpKernel;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Class Kernel.
 */
class Kernel extends \Symfony\Component\HttpKernel\Kernel
{
    /**
     * @var string
     */
    const ENV_PROD = KernelDefinition::ENV_PROD;

    /**
     * @var string
     */
    const ENV_DEV = KernelDefinition::ENV_DEV;

    /**
     * @var string
     */
    const ENV_TEST = KernelDefinition::ENV_TEST;

    /**
     * @var string[]
     */
    const ENV_ALL = KernelDefinition::ENV_ALL;

    /**
     * @var KernelDefinition[]
     */
    protected static $definitions = [];

    /**
     * Overwrite in your own class and call {@register()} to add bundles.
     */
    protected static function setupDefinitions()
    {
    }

    /**
     * Clear definitions variable.
     */
    final protected static function clearDefinitions()
    {
        static::$definitions = [];
    }

    /**
     * @param string $fqcn
     *
     * @return KernelDefinition
     */
    final protected static function register($fqcn)
    {
        static::$definitions[] = $definition = KernelDefinition::create($fqcn);
        $definition->environments(self::ENV_ALL);

        return $definition;
    }

    /**
     * @param string $environment
     *
     * @return KernelDefinition[]
     */
    final protected function filterBundles($environment)
    {
        return array_filter(static::$definitions, function (KernelDefinition $definition) use ($environment) {
            return $definition->hasEnvironment($environment);
        });
    }

    /**
     * @return BundleInterface[]
     */
    final public function registerBundles()
    {
        static::clearDefinitions();
        static::setupDefinitions();

        return array_map(function (KernelDefinition $definition) {
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
