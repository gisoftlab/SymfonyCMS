<?php

namespace App\CoreBundle\Libraries;

class jsonAlternative
{

    /**
     * jsonEncode
     * @param bool $a
     * @return string
     */
    static function jsonEncode($a = false)
    {
        if (is_null($a)) {
            return 'null';
        }
        if ($a === false) {
            return 'false';
        }
        if ($a === true) {
            return 'true';
        }
        if (is_scalar($a)) {
            if (is_float($a)) {
                // Always use "." for floats.
                return floatval(str_replace(",", ".", strval($a)));
            }

            if (is_string($a)) {
                static $jsonReplaces = array(
                    array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'),
                    array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'),
                );

                return '"'.str_replace($jsonReplaces[0], $jsonReplaces[1], $a).'"';
            } else {
                return $a;
            }
        }
        $isList = true;
        for ($i = 0, reset($a); $i < count($a); $i++, next($a)) {
            if (key($a) !== $i) {
                $isList = false;
                break;
            }
        }
        $result = array();
        if ($isList) {
            foreach ($a as $v) {
                $result[] = self::jsonEncode($v);
            }

            return '['.join(',', $result).']';
        } else {
            foreach ($a as $k => $v) {
                $result[] = self::jsonEncode($k).':'.self::jsonEncode($v);
            }

            return '{'.join(',', $result).'}';
        }
    }
}


