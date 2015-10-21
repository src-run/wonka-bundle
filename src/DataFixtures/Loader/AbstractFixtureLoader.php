<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\DataFixtures\Loader;

use Scribe\Wonka\Exception\RuntimeException;
use Symfony\Component\Config\Loader\LoaderResolverInterface;

/**
 * AbstractFixtureLoader.
 */
abstract class AbstractFixtureLoader implements FixtureLoaderInterface
{
    /**
     * @var LoaderResolverInterface
     */
    private $resolver;

    /**
     * @param LoaderResolverInterface $resolver
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @return LoaderResolverInterface
     */
    public function getResolver()
    {
        return $this->resolver;
    }

    /**
     * @param mixed      $resource
     * @param null|mixed $type
     *
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return false;
    }

    /**
     * @param mixed $resource
     * @param null  $type
     */
    abstract public function load($resource, $type = null);

    /**
     * @param string $resource
     *
     * @return string
     */
    protected function loadFileContents($resource)
    {
        try {
            return file_get_contents($resource);
        } catch (\Exception $exception) {
            throw new RuntimeException('Could not get file contents for %s.', null, $exception, $resource);
        }
    }

    /**
     * @param mixed $resource
     *
     * @return string
     */
    protected function getResourceType($resource)
    {
        if (is_string($resource)) {
            throw new RuntimeException('Invalid resource provided to fixture loader.');
        }

        if (!($type = strtolower(pathinfo($resource, PATHINFO_EXTENSION)))) {
            throw new RuntimeException('Could not determine resource extension.');
        }

        return (string) $type;
    }
}

/* EOF */
