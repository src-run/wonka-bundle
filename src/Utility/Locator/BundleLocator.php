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

namespace SR\WonkaBundle\Utility\Locator;

use SR\Utility\ClassInspect;
use SR\Utility\StringTransform;
use SR\Wonka\Utility\StaticClass\StaticClassTrait;

/**
 * Class BundleLocator.
 */
class BundleLocator
{
    use StaticClassTrait;

    /**
     * @param string $namespace
     *
     * @return array[]
     */
    public static function getContextFromNamespace($namespace)
    {
        list($nameRoot, $nameSections) = self::parseNamespaceContext($namespace);

        $namespaceSections = [];

        foreach (self::getNamespaceSections($namespace) as $section) {
            $namespaceSections[] = $section;

            if (1 === preg_match('{Bundle$}', $section)) {
                break;
            }
        }

        return ['\\'.implode('\\', array_merge($namespaceSections)), $nameRoot, self::generateBundleName($nameSections)];
    }

    /**
     * @param string $namespace
     *
     * @return string
     */
    public final static function getName($namespace)
    {
        list($nameRoot, $nameSections) = self::parseNamespaceContext($namespace);

        return self::generateBundleName($nameSections, $nameRoot);
    }

    /**
     * @param string $namespace
     *
     * @return string[]|array[]
     */
    private final static function parseNamespaceContext($namespace)
    {
        $sections = self::getNamespaceSections(self::normalizeSequentialUpperChars($namespace));

        if (ClassInspect::isClass($namespace)) {
            array_pop($sections);
        }

        $root = array_shift($sections);
        $root = StringTransform::pascalToSnakeCase($root);

        return [$root, $sections];
    }

    /**
     * @param string[]    $sections
     * @param null|string $rootName
     *
     * @return string
     */
    private final static function generateBundleName(array $sections, $nameRoot = null)
    {
        $nameSections = [];

        for ($i = 0; $i < count($sections); $i++) {
            $nameSections[] = StringTransform::pascalToSnakeCase($sections[$i]);

            if (substr($sections[$i], -6) === 'Bundle') {
                break;
            }
        }

        if ($nameRoot !== null) {
            $nameSections = array_merge((array)$nameRoot, $nameSections);
        }

        return implode('_', $nameSections);
    }

    /**
     * @param string $namespace
     *
     * @return string
     */
    private final static function normalizeSequentialUpperChars($namespace)
    {
        return preg_replace_callback(
            '{([A-Z][A-Z]+)(\\\\)}',
            function ($matches) {
                return strtolower($matches[1]).'\\';
            },
            $namespace
        );
    }

    protected final static function getNamespaceSections($namespace, $includesClass = false)
    {
        $parts = explode('\\', $namespace);

        if ($includesClass) {
            var_dump([__METHOD__, array_pop($parts)]);
        }

        return $parts;
    }
}

/* EOF */
