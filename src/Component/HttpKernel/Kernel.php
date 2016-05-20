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

use SR\Reflection\Inspect;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class Kernel.
 */
class Kernel extends BaseKernel
{
    /**
     * @var array[]
     */
    protected $enviornmentRegistrations;

    /**
     * @return $this
     */
    public function clear()
    {
        $this->enviornmentRegistrations = ['all' => []];

        return $this;
    }

    /**
     * @param string   $qualifiedName
     * @param string[] $enviornments
     *
     * @return $this
     */
    final protected function register($qualifiedName, ...$enviornments)
    {
        $enviornments = count($enviornments) === 0 ? ['all'] : $enviornments;

        if (substr($qualifiedName, 0, 1) !== '\\') {
            $qualifiedName = '\\'.$qualifiedName;
        }

        foreach ($enviornments as $e) {
            if (!array_key_exists($e, $this->enviornmentRegistrations)) {
                $this->enviornmentRegistrations[$e] = [];
            }

            $this->enviornmentRegistrations[$e][] = $qualifiedName;
        }

        return $this;
    }

    /**
     * @return array[]
     */
    final protected function resolveBundles()
    {
        $unresolved = array_unique($this->enviornmentRegistrations['all']);
        $resolved = [];
        $enviornment = $this->getEnvironment();

        if ($enviornment !== 'prod' && array_key_exists($enviornment, $this->enviornmentRegistrations)) {
            $unresolved = array_unique(array_merge($unresolved, $this->enviornmentRegistrations[$enviornment]));
        }

        foreach ($unresolved as $bundle) {
            $resolved[] = new $bundle();
        }

        var_dump($resolved);

        return $resolved;
    }

    /**
     * @return object[]
     */
    final public function registerBundles()
    {
        $this->clear();
        $this->setup();

        return $this->resolveBundles();
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
