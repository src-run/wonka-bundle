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

use SR\Exception\InvalidArgumentException;
use SR\Exception\RuntimeException;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Class KernelDefinition.
 */
class KernelDefinition
{
    /**
     * @var string
     */
    const ENV_PROD = 'prod';

    /**
     * @var string
     */
    const ENV_DEV = 'dev';

    /**
     * @var string
     */
    const ENV_TEST = 'test';

    /**
     * @var string
     */
    const ENV_ALL = [self::ENV_PROD, self::ENV_DEV, self::ENV_TEST];

    /**
     * @var string
     */
    private $fqcn;

    /**
     * @var string[]
     */
    private $environments = [];

    /**
     * @var mixed[]
     */
    private $arguments = [];

    /**
     * @param string $fqcn
     */
    public function __construct($fqcn)
    {
        if (substr($fqcn, 0, 1) !== '\\') {
            $fqcn = '\\'.$fqcn;
        }

        if (!class_exists($fqcn)) {
            throw InvalidArgumentException::create('Cannot create bundle instance definition for (%s): class does not exist.', $fqcn);
        }

        $this->fqcn = $fqcn;
    }

    /**
     * @param string $fqcn
     *
     * @return static
     */
    public static function create($fqcn)
    {
        return new static($fqcn);
    }

    /**
     * @param string[] ...$environments
     *
     * @return $this
     */
    public function environments(...$environments)
    {
        $flattened = [];

        foreach (new \RecursiveIteratorIterator(new \RecursiveArrayIterator($environments)) as $e) {
            if (in_array($e, self::ENV_ALL)) {
                $flattened[] = $e;
            }
        }

        $this->environments = array_unique($flattened);

        return $this;
    }

    /**
     * @param string $environment
     *
     * @return bool
     */
    public function hasEnvironment($environment)
    {
        return in_array($environment, $this->environments);
    }

    /**
     * @param mixed[] ...$arguments
     *
     * @return $this
     */
    public function arguments(...$arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @return BundleInterface
     */
    public function getInstance()
    {
        $rc = new \ReflectionClass($this->fqcn);

        if (!$rc->isInstantiable()) {
            throw new RuntimeException('Bundle (%s) is not instantiable', $this->fqcn);
        }

        if (!$rc->implementsInterface(BundleInterface::class)) {
            throw new RuntimeException('Bundle (%s) must implement BundleInterface', $this->fqcn);
        }

        return $rc->newInstanceArgs($this->arguments);
    }
}

/* EOF */
