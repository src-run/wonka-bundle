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

namespace SR\WonkaBundle\Component\HttpKernel\Bundle;

use SR\Utility\ClassInspect;
use SR\Utility\StringTransform;

/**
 * Class BundleInspect.
 */
class BundleInspect
{
    /**
     * @param string $namespace
     *
     * @return mixed[]
     */
    public final static function getContextFromNamespace($namespace)
    {
        list($root, $sections) = self::parseNamespaceContext($namespace);

        $namespaceSections = [];

        foreach (self::getNamespaceSections($namespace) as $section) {
            $namespaceSections[] = $section;

            if (1 === preg_match('{Bundle$}', $section)) {
                break;
            }
        }

        $namespace = '\\'.implode('\\', array_merge($namespaceSections));

        return [$namespace, $root, self::generateBundleName($sections)];
    }

    /**
     * @param string $namespace
     *
     * @return string
     */
    public final static function getName($namespace)
    {
        list($root, $sections) = self::parseNamespaceContext($namespace);

        return self::generateBundleName($sections, $root);
    }

    /**
     * @param string $namespace
     *
     * @return string[]|array[]
     */
    private final static function parseNamespaceContext($namespace)
    {
        $sections = self::getNamespaceSections(
            self::normalizeSequentialUpperChars($namespace));

        if (ClassInspect::isClass($namespace)) {
            array_pop($sections);
        }

        $root = StringTransform::pascalToSnakeCase(array_shift($sections));

        return [$root, $sections];
    }

    /**
     * @param string[]    $from
     * @param null|string $root
     *
     * @return string
     */
    private final static function generateBundleName(array $from, $root = null)
    {
        $sections = [];

        for ($i = 0; $i < count($from); $i++) {
            $sections[] = StringTransform::pascalToSnakeCase($from[$i]);

            if (1 === preg_match('{Bundle$}', $from[$i])) {
                break;
            }
        }

        if ($root !== null) {
            $sections = array_merge((array)$root, $sections);
        }

        return implode('_', $sections);
    }

    /**
     * @param string $namespace
     *
     * @return string
     */
    private final static function normalizeSequentialUpperChars($namespace)
    {
        return preg_replace_callback('{([A-Z][A-Z]+)(\\\\)}', function ($matches) {
            return strtolower($matches[1]).'\\';
        }, $namespace);
    }

    /**
     * @param string $namespace
     * @param bool   $isQualifiedClass
     *
     * @return array
     */
    private final static function getNamespaceSections($namespace, $isQualifiedClass = false)
    {
        $parts = explode('\\', $namespace);

        if ($isQualifiedClass) {
            array_pop($parts);
        }

        return $parts;
    }
}

/* EOF */
