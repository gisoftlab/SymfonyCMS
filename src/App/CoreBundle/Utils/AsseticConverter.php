<?php

namespace App\CoreBundle\Utils;

/**
 * This class is used to provide an example of integrating simple classes as
 * services into a Symfony application.
 *
 */
class AsseticConverter
{
    /**
     * GET escapeJavaScriptText
     *
     * @param string $string
     * @return string
     */
    public static function escapeJavaScriptText($string)
    {
        return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\")));
    }

    /**
     * GET convertToLine
     *
     * @param string $string
     * @return string
     */
    public static function convertToLine($string)
    {
        $newString = "";
        foreach ( explode("\n",$string) as $index => $item) {
            if(strpos($item, "<script") === false) {
                if (strpos($item, "script>") === false) {
                    $newString .= $item;
                    if (!trim($item)) {
                        $newString .= trim($item);
                    }
                }
            }
        }

        $newString = str_replace(array("\r\n", "\n", "\r"), '', $newString);
        $newString = str_replace("  "," ", $newString);

        return $newString;
    }
}
