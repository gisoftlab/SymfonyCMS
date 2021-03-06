<?php

/*
 * This file is part of php-cache organization.
 *
 * (c) 2015-2015 Aaron Scherer <aequasi@gmail.com>, Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\CoreBundle\Libraries\Cache;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
trait HierarchicalCachePoolTrait
{
    static $HIERARCHY_SEPARATOR = '|';
    static $TAG_SEPARATOR = '!';

    /**
     * Get a key to use with the hierarchy. If the key does not start with HierarchicalPoolInterface::SEPARATOR
     * this will return an unalterered key. This function supports a tagged key. Ie "foo:bar".
     *
     * @param string $key      The original key
     * @param string &$pathKey A cache key for the path. If this key is changed everything beyond that path is changed.
     *
     * @return string
     */
    protected function getHierarchyKey($key, &$keyCache = array())
    {
        if (!$this->isHierarchyKey($key)) {
            return $key;
        }

        $key = $this->explodeKey($key);

        $keyString = '';
        // The comments below is for a $key = ["foo!tagHash", "bar!tagHash"]
        foreach ($key as $name) {
            // 1) $keyString = "foo!tagHash"
            // 2) $keyString = "foo!tagHash![foo_index]!bar!tagHash"
            $keyString .= $name;
            $pathKey = sha1('path'.self::$TAG_SEPARATOR.$keyString);

            if (isset($keyCache[$pathKey])) {
                $index = $keyCache[$pathKey];
            } else {
                $index              = $name;
                $keyCache[$pathKey] = $index;
            }

            // 1) $keyString = "foo!tagHash![foo_index]!"
            // 2) $keyString = "foo!tagHash![foo_index]!bar!tagHash![bar_index]!"
            $keyString .= self::$TAG_SEPARATOR.$index.self::$TAG_SEPARATOR;
        }

        // Assert: $pathKey = "path!foo!tagHash![foo_index]!bar!tagHash"
        // Assert: $keyString = "foo!tagHash![foo_index]!bar!tagHash![bar_index]!"
        // Make sure we do not get awfully long (>250 chars) keys
        return sha1($keyString);
    }

    /**
     * getHierarchySubKey
     * @param $key
     * @return array|string
     */
    protected function getHierarchySubKey($key)
    {
        if (!$this->isHierarchyKey($key)) {
            return $key;
        }
        $key = $this->explodeKey($key);

        $keyString = '';
        $pathKey = '';
        // The comments below is for a $key = ["foo!tagHash", "bar!tagHash"]
        foreach ($key as $name) {
            // 1) $keyString = "foo!tagHash"
            // 2) $keyString = "foo!tagHash![foo_index]!bar!tagHash"
            $keyString .= $name;
            $pathKey = sha1('path'.self::$TAG_SEPARATOR.$keyString);
            // 1) $keyString = "foo!tagHash![foo_index]!"
            // 2) $keyString = "foo!tagHash![foo_index]!bar!tagHash![bar_index]!"
            $keyString .= self::$TAG_SEPARATOR.$name.self::$TAG_SEPARATOR;
        }

        // Assert: $pathKey = "path!foo!tagHash![foo_index]!bar!tagHash"
        // Assert: $keyString = "foo!tagHash![foo_index]!bar!tagHash![bar_index]!"

        // Make sure we do not get awfully long (>250 chars) keys
        return $pathKey;
    }

    /**
     * A hierarchy key MUST begin with the separator.
     *
     * @param string $key
     *
     * @return bool
     */
    private function isHierarchyKey($key)
    {
        return substr($key, 0, 1) === self::$HIERARCHY_SEPARATOR;
    }

    /**
     * This will take a hierarchy key ("|foo|bar") with tags ("|foo|bar!tagHash") and return an array with
     * each level in the hierarchy appended with the tags. ["foo!tagHash", "bar!tagHash"].
     *
     * @param string $key
     *
     * @return array
     */
    private function explodeKey($string)
    {
        list($key, $tag) = explode(self::$TAG_SEPARATOR, $string.self::$TAG_SEPARATOR);

        if ($key === self::$HIERARCHY_SEPARATOR) {
            $parts = ['root'];
        } else {
            $parts = explode(self::$HIERARCHY_SEPARATOR, $key);
            // remove first element since it is always empty and replace it with 'root'
            $parts[0] = 'root';
        }

        return array_map(function ($level) use ($tag) {
            return $level.self::$TAG_SEPARATOR.$tag;
        }, $parts);
    }
}
