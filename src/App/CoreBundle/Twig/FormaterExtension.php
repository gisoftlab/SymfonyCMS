<?php

namespace App\CoreBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Twig Extension for Formater support.
 *
 * @author damian <info@gisoft.pl>
 * @package App\CoreBundle\Twig\Extension
 */
class FormaterExtension extends \Twig_Extension {

    /**
     * Container
     *
     * @var ContainerInterface
     */
    protected static $_container;

    /**
     * Initialize Formater helper
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {

        self::$_container = $container;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('ucwords', array($this, 'ucwords')),
            new \Twig_SimpleFunction('sizeof', array($this, 'sizeof')),
            new \Twig_SimpleFunction('ucfirst', array($this, 'ucfirst')),
            new \Twig_SimpleFunction('strtotime', array($this, 'strtotime')),
            new \Twig_SimpleFunction('str_replace', array($this, 'str_replace')),
            new \Twig_SimpleFunction('tags', array($this, 'tags')),
            new \Twig_SimpleFunction('count', array($this, 'count')),
            new \Twig_SimpleFunction('slug', array($this, 'slug')),
            new \Twig_SimpleFunction('slugify', array($this, 'slugify')),
            new \Twig_SimpleFunction('date', array($this, 'date')),
            new \Twig_SimpleFunction('strpos', array($this, 'strpos')),
            new \Twig_SimpleFunction('strlen', array($this, 'strlen')),
            new \Twig_SimpleFunction('htmlspecialchars', array($this, 'htmlspecialchars')),
            new \Twig_SimpleFunction('round', array($this, 'round')),
            new \Twig_SimpleFunction('number_format', array($this, 'number_format')),
            new \Twig_SimpleFunction('cleanString', array($this, 'cleanString')),
            new \Twig_SimpleFunction('trimstr', array($this, 'trimstr')),
            new \Twig_SimpleFunction('moveMishmashLetter', array($this, 'moveMishmashLetter')),
            new \Twig_SimpleFunction('generateSlug', array($this, 'generateSlug')),
            new \Twig_SimpleFunction('is_readable', array($this, 'is_readable')),
            new \Twig_SimpleFunction('isReadableSource', array($this, 'isReadableSource')),
            new \Twig_SimpleFunction('format_without_accented_chars', array($this, 'format_without_accented_chars')),
            new \Twig_SimpleFunction('search_escape_for_url', array($this, 'search_escape_for_url')),
            new \Twig_SimpleFunction('get_controller_name', array($this, 'get_controller_name')),
            new \Twig_SimpleFunction('get_bundle_name', array($this, 'get_bundle_name')),
            new \Twig_SimpleFunction('getUri', array($this, 'getUri')),
            new \Twig_SimpleFunction('getIIter', array($this, 'getIIter')),
            new \Twig_SimpleFunction('getUrl', array($this, 'getUrl')),
            new \Twig_SimpleFunction('getPathUri', array($this, 'getPathUri')),
            new \Twig_SimpleFunction('getPortalWidth', array($this, 'getPortalWidth')),
            new \Twig_SimpleFunction('form_enctype', array($this, 'formEnctype')),
        );
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('var_dump', array($this, 'var_dump')),
            new \Twig_SimpleFilter('exit', array($this, 'sexit')),
            new \Twig_SimpleFilter('strpos', array($this, 'strpos')),
            new \Twig_SimpleFilter('safeTags', array($this, 'safeTags')),
            new \Twig_SimpleFilter('urlencode', array($this, 'urlencode')),
            new \Twig_SimpleFilter('ucwords', array($this, 'ucwords')),
            new \Twig_SimpleFilter('substr', array($this, 'substr')),
            new \Twig_SimpleFilter('truncate', array($this, 'truncate')),
            new \Twig_SimpleFilter('truncateHtml', array($this, 'truncateHtml')),
            new \Twig_SimpleFilter('safeTags', array($this, 'safeTags')),
            new \Twig_SimpleFilter('cleanString', array($this, 'cleanString')),
        );
    }

    /**
     * @param $string
     * @param int $length
     * @param string $appendStr
     * @return string
     */
    function truncate($string, $length = 100, $appendStr = "...") {
        $useAppendStr = (strlen($string) > intval($length)) ? true : false;
        $truncated_str = substr($string, 0, $length);
        $truncated_str .= ($useAppendStr) ? $appendStr : "";
        return $truncated_str;
    }

    /**
     * @return mixed|string
     */
    function getURL() {

        $uri = str_replace($_SERVER["SCRIPT_NAME"], "", self::geturi());

        if (strpos($uri, "?") !== false)
            $uri = substr($uri, 0, strpos($uri, "?"));

        $temp = explode("/", $uri);

        if (count($temp) >= 4)
            $uri = "/" . $temp[1] . "/" . $temp[2] . "/" . $temp[3];

        return $uri;
    }

    /**
     * @param $arr
     * @return int
     */
    public static function count($arr) {
        return count($arr);
    }

    public static function sexit() {
        exit;
    }

    /**
     * @return array
     */
    public static function getUri() {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * @return mixed
     */
    public static function getPortalWidth() {
        return self::$_container->getParameter("max.portal.width");
    }

    /**
     * @return mixed
     */
    public static function getPathUri() {

        $uri = $_SERVER["REQUEST_URI"] ? $_SERVER["REQUEST_URI"] : "";
        $script = $_SERVER["SCRIPT_NAME"] ? $_SERVER["SCRIPT_NAME"] : "";

        return str_replace($script, "", $uri);
    }

    /**
     * @param $paginator
     * @param $key
     * @return int
     */
    public static function getIIter($paginator, $key) {
        $key += 1;
        /*
            TODO check Old solution
            $page = $paginator->getCurrentPageNumber();
            $limit = $paginator->getItemNumberPerPage();
            return $page*$limit - $limit + $key;
        */

        return $key;
    }

    /**
     * @param $request_attributes
     * @return mixed|null
     */
    public function getControllerName($request_attributes) {

        $pattern = "/Controller\\\\([a-zA-Z]*)Controller/";
        $matches = array();
        preg_match($pattern, $request_attributes->get("_controller"), $matches);

        if (isset($matches[1])) {
            return $matches[1];
        } else {
            return null;
        }
    }

    /**
     * @param $request_attributes
     * @return mixed|null
     */
    public function getBundleName($request_attributes) {
        $pattern = "/App\\\\([a-zA-Z]*)Bundle/";
        $matches = array();
        preg_match($pattern, $request_attributes->get("_controller"), $matches);

        if (isset($matches[1])) {
            return $matches[1];
        } else {
            return null;
        }
    }

    /**
     * @param $posts
     * @return array
     */
    public function getTags($posts) {
        $tags = array();
        foreach ($posts as $post) {
            foreach ($post->getTag() as $tag)
                if (!in_array($tag, $tags))
                    $tags[] = $tag;
        }
        return $tags;
    }

    /**
     * @param $items
     * @return int
     */
    public function getCount($items) {
        return count($items);
    }

    /**
     * @param $items
     * @return int
     */
    public function formEnctype() {
        return 'enctype="multipart/form-data"';
    }



    /**
     * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
     *
     * @param string $text String to truncate.
     * @param integer $length Length of returned string, including ellipsis.
     * @param string $ending Ending to be appended to the trimmed string.
     * @param boolean $exact If false, $text will not be cut mid-word
     * @param boolean $considerHtml If true, HTML tags would be handled correctly
     *
     * @return string Trimmed string.
     */
    public function truncateHtml($text, $length = 100, $considerHtml = false, $exact = false,  $ending = '...') {
        $open_tags = array();

        if ($considerHtml) {
            $text = str_replace('"', "", $text);
            // if the plain text is shorter than the maximum length, return the whole text
            if ((strlen(preg_replace('/<.*?>/', '', $text)) <= $length) && ($length)) {
                return $text;
            }


            // splits all html-tags to scanable lines
            preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
            $total_length = strlen($ending);
            $truncate = '';
            foreach ($lines as $line_matchings) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($line_matchings[1])) {
                    // if it's an "empty element" with or without xhtml-conform closing slash
                    if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                        // do nothing
                        // if tag is a closing tag
                    } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                        // delete tag from $open_tags list
                        $pos = array_search($tag_matchings[1], $open_tags);
                        if ($pos !== false) {
                            unset($open_tags[$pos]);
                        }
                        // if tag is an opening tag
                    } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                        // add tag to the beginning of $open_tags list
                        array_unshift($open_tags, strtolower($tag_matchings[1]));
                    }
                    // add html-tag to $truncate'd text
                    $truncate .= $line_matchings[1];
                }
                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length+$content_length> $length) {
                    // the number of characters which are left
                    $left = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($entity[1]+1-$entities_length <= $left) {
                                $left--;
                                $entities_length += strlen($entity[0]);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                    // maximum lenght is reached, so get off the loop
                    break;
                } else {
                    $truncate .= $line_matchings[2];
                    $total_length += $content_length;
                }
                // if the maximum length is reached, get off the loop
                if($total_length>= $length) {
                    break;
                }
            }
        } else {
            if (strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = substr($text, 0, $length - strlen($ending));
            }
        }
        // if the words shouldn't be cut in the middle...
        if (!$exact) {
            // ...search the last occurance of a space...
            $spacepos = strrpos($truncate, ' ');
            if (isset($spacepos)) {
                // ...and cut the text in this position
                $truncate = substr($truncate, 0, $spacepos);
            }
        }
        // add the defined ending to the text
        if($length)
            $truncate .= $ending;
        if($considerHtml) {
            // close all unclosed html-tags
            foreach ($open_tags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }
        return $truncate;
    }

    /**
     * @param $sentence
     * @param string $keep
     * @param string $expand
     * @return string
     */
    public function safeTags($sentence, $keep = '', $expand = 'script|noframes|link|script|iframe|frame') {
        $s = ' ' . $sentence;
        $k = "";

        if (strlen($keep) > 0) {
            $k = explode('|', $keep);
            for ($i = 0; $i < count($k); $i++) {
                $s = str_replace('<' . $k[$i], '[{(' . $k[$i], $s);
                $s = str_replace('</' . $k[$i], '[{(/' . $k[$i], $s);
            }
        }

        if (strlen($expand) > 0) {
            $e = explode('|', $expand);
            for ($i = 0; $i < count($e); $i++) {
                while (stripos($s, '<' . $e[$i]) > 0) {
                    $len[1] = strlen('<' . $e[$i]);
                    $pos[1] = stripos($s, '<' . $e[$i]);
                    $pos[2] = stripos($s, $e[$i] . '>', $pos[1] + $len[1]);
                    $len[2] = $pos[2] - $pos[1] + $len[1];
                    $x = substr($s, $pos[1], $len[2]);
                    $s = str_replace($x, '', $s);
                }
            }
        }

        if (strlen($keep) > 0) {
            for ($i = 0; $i < count($k); $i++) {
                $s = str_replace('[{(' . $k[$i], '<' . $k[$i], $s);
                $s = str_replace('[{(/' . $k[$i], '</' . $k[$i], $s);
            }
        }
        return trim($s);
    }


    /**
     * @param $haystack
     * @param $needle
     * @return bool|int
     */
    public static function strpos($haystack, $needle) {
        if(($index = strpos($haystack, $needle)) !== false)
            return $index;

        return false;
    }

    /**
     * @param $expression
     * @param null $_
     */
    public static function var_dump($expression, $_ = null) {
        return var_dump($expression, $_);
    }

    /**
     * @param $string
     * @return int
     */
    public static function strlen($string) {
        return strlen($string);
    }

    /**
     * @param $string
     * @param int $flags
     * @param string $encoding
     * @param bool $double_encode
     * @return string
     */
    public static function htmlspecialchars($string, $flags = ENT_HTML401, $encoding = "UTF-8", $double_encode = true) {
        return htmlspecialchars($string, $flags, $encoding, $double_encode);
    }

    /**
     * @param $val
     * @param int $precision
     * @param int $mode
     * @return float
     */
    public static function round($val, $precision = 0, $mode = PHP_ROUND_HALF_UP) {
        return round($val, $precision, $mode);
    }

    /**
     * @param $haystack
     * @param $index
     * @param $before_needle
     * @return string
     */
    public static function substr($haystack, $index, $before_needle) {
        return substr($haystack, $index, $before_needle);
    }

    /**
     * @param $str
     * @return string
     */
    public static function urlencode($str) {
        return urlencode($str);
    }

    /**
     * @param $str
     * @return string
     */
    public static function ucwords($str) {
        return ucwords($str);
    }

    /**
     * @param $number
     * @param int $decimals
     * @param string $dec_point
     * @param string $thousands_sep
     * @return string
     */
    public static function number_format($number, $decimals = 0, $dec_point = ".", $thousands_sep = ",") {
        return number_format($number, $decimals, $dec_point, $thousands_sep);
    }

    /**
     * @param $str
     * @return string
     */
    public static function ucfirst($str) {
        return ucfirst($str);
    }

    /**
     * @param $time
     * @param null $now
     * @return false|int
     */
    public static function strtotime($time, $now = null) {
        $now = ($now)?$now:time();
        return strtotime($time, $now);
    }

    /**
     * @param $search
     * @param $replace
     * @param $subject
     * @param null $count
     * @return mixed
     */
    public static function str_replace($search, $replace, $subject, &$count = null) {
        return str_replace($search, $replace, $subject, $count);
    }

    /**
     * @param $var
     * @return int
     */
    public static function sizeof($var) {
        return sizeof($var);
    }

    /**
     * @param $filename
     * @return bool
     */
    public static function is_readable($filename) {

        $documentRoot  = $_SERVER["DOCUMENT_ROOT"];

        $documentRoot = str_replace("/web/", "", $documentRoot);
        $documentRoot = str_replace("/web", "", $documentRoot);

        return is_readable($documentRoot.$filename);
    }

    /**
     * @param $folder
     * @param $filename
     * @return bool
     */
    public static function isReadableSource($folder, $filename) {
        return self::is_readable("/web/uploads/".$folder."/".$filename);
    }

    /**
     * @param null $date
     * @param string $expresion
     * @param string $mask
     * @return false|string
     */
    public static function date($date = null, $expresion = "", $mask = "d/m/Y") {
        $date = ($date) ? $date : time();
        return date($mask, strtotime($expresion, $date));
    }

    /**
     * @param $url
     * @return bool|mixed|string
     */
    public static function slug($url) {
        $url = preg_replace("`\[.*\]`U", "", $url);
        $url = preg_replace('`&(amp;)?#?[a-z0-9]+;`i', '-', $url);
        $url = htmlentities($url, ENT_NOQUOTES, 'UTF-8');
        $url = preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i", "\\1", $url);
        $url = preg_replace(array("`[^a-z0-9]`i", "`[-]+`"), "-", $url);
        $url = ( $url == "" ) ? false : strtolower(trim($url, '-'));
        return $url;
    }

    /**
     * @param $text
     * @return string
     */
    public static function slugify($text) {

        $text = trim(self::format_without_accented_chars($text));

        // strip all non word chars
        $text = preg_replace('/[^a-z0-9_-|,]/i', ' ', $text);

        // strtolower is not utf8-safe, therefore it can only be done after special characters replacement
        $text = strtolower($text);

        // replace multiwhitespaces to one
        $text = str_replace("  ", ' ', $text);

        // replace all white space sections with a dash
        $text = preg_replace('/\ +/', '-', $text);

        // trim dashes
        $text = preg_replace('/\-$/', '', $text);
        $text = preg_replace('/^\-/', '', $text);

        return self::trimstr($text, 80, "", "CHARS");
    }


    /**
     * @param $text
     * @return mixed
     */
    public static function format_without_accented_chars($text) {
        // French
        $text = str_replace(array('À', 'Â', 'à', 'â'), 'a', $text);
        $text = str_replace(array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ê', 'ë'), 'e', $text);
        $text = str_replace(array('Î', 'Ï', 'î', 'ï'), 'i', $text);
        $text = str_replace(array('Ô', 'ô'), 'o', $text);
        $text = str_replace(array('Ù', 'Û', 'ù', 'û'), 'u', $text);
        $text = str_replace(array('Ç', 'ç'), 'c', $text);
        // German
        $text = str_replace(array('Ä', 'ä'), 'ae', $text);
        $text = str_replace(array('Ö', 'ö'), 'oe', $text);
        $text = str_replace(array('Ü', 'ü'), 'ue', $text);
        $text = str_replace('ß', 'ss', $text);
        // Spanish
        $text = str_replace(array('Ñ', 'ñ'), 'n', $text);
        $text = str_replace(array('Á', 'á'), 'a', $text);
        $text = str_replace(array('Í', 'í'), 'i', $text);
        $text = str_replace(array('Ó', 'ó'), 'o', $text);
        $text = str_replace(array('Ú', 'ú'), 'u', $text);

        // Polish
        $text = str_replace(array('ą', 'Ą'), 'a', $text);
        $text = str_replace(array('ś', 'Ś'), 's', $text);
        $text = str_replace(array('ż', 'Ż'), 'z', $text);
        $text = str_replace(array('ź', 'Ź'), 'z', $text);
        $text = str_replace(array('ć', 'Ć'), 'c', $text);
        $text = str_replace(array('ó', 'Ó'), 'o', $text);
        $text = str_replace(array('ł', 'ł'), 'l', $text);
        $text = str_replace(array('ę', 'Ę'), 'e', $text);
        $text = str_replace(array('ń', 'Ń'), 'n', $text);

        return $text;
    }

    /**
     * @param $v
     * @return mixed|string
     */
    public static function search_escape_for_url($v) {
        $v = trim(self::format_without_accented_chars($v));

        $v = str_replace(array(' -', '- '), '-', $v);
        // replace all white space
        $v = preg_replace('/\ +/', '_', $v);
        // trim dashes
        $v = preg_replace('/\-$/', '', $v);
        $v = preg_replace('/^\-/', '', $v);

        return $v;
    }

    /**
     * @param $text
     * @param bool $withspace
     * @return mixed
     */
    public static  function cleanString($text, $withspace = true) {
        // 1) convert á ô => a o
        $text = preg_replace("/[áàâãªä]/u","a",$text);
        $text = preg_replace("/[ÁÀÂÃÄ]/u","A",$text);
        $text = preg_replace("/[ÍÌÎÏ]/u","I",$text);
        $text = preg_replace("/[íìîï]/u","i",$text);
        $text = preg_replace("/[éèêë]/u","e",$text);
        $text = preg_replace("/[ÉÈÊË]/u","E",$text);
        $text = preg_replace("/[óòôõºö]/u","o",$text);
        $text = preg_replace("/[ÓÒÔÕÖ]/u","O",$text);
        $text = preg_replace("/[úùûü]/u","u",$text);
        $text = preg_replace("/[ÚÙÛÜ]/u","U",$text);
        $text = preg_replace("/[’‘‹›‚]/u","'",$text);
        $text = preg_replace("/[“”«»„]/u",'"',$text);
        $text = str_replace("–","-",$text);
        $text = str_replace(" "," ",$text);
        $text = str_replace("ç","c",$text);
        $text = str_replace("Ç","C",$text);
        $text = str_replace("ñ","n",$text);
        $text = str_replace("Ñ","N",$text);

        //2) Translation CP1252. &ndash; => -
        $trans = get_html_translation_table(HTML_ENTITIES);
        $trans[chr(130)] = '&sbquo;';    // Single Low-9 Quotation Mark
        $trans[chr(131)] = '&fnof;';    // Latin Small Letter F With Hook
        $trans[chr(132)] = '&bdquo;';    // Double Low-9 Quotation Mark
        $trans[chr(133)] = '&hellip;';    // Horizontal Ellipsis
        $trans[chr(134)] = '&dagger;';    // Dagger
        $trans[chr(135)] = '&Dagger;';    // Double Dagger
        $trans[chr(136)] = '&circ;';    // Modifier Letter Circumflex Accent
        $trans[chr(137)] = '&permil;';    // Per Mille Sign
        $trans[chr(138)] = '&Scaron;';    // Latin Capital Letter S With Caron
        $trans[chr(139)] = '&lsaquo;';    // Single Left-Pointing Angle Quotation Mark
        $trans[chr(140)] = '&OElig;';    // Latin Capital Ligature OE
        $trans[chr(145)] = '&lsquo;';    // Left Single Quotation Mark
        $trans[chr(146)] = '&rsquo;';    // Right Single Quotation Mark
        $trans[chr(147)] = '&ldquo;';    // Left Double Quotation Mark
        $trans[chr(148)] = '&rdquo;';    // Right Double Quotation Mark
        $trans[chr(149)] = '&bull;';    // Bullet
        $trans[chr(150)] = '&ndash;';    // En Dash
        $trans[chr(151)] = '&mdash;';    // Em Dash
        $trans[chr(152)] = '&tilde;';    // Small Tilde
        $trans[chr(153)] = '&trade;';    // Trade Mark Sign
        $trans[chr(154)] = '&scaron;';    // Latin Small Letter S With Caron
        $trans[chr(155)] = '&rsaquo;';    // Single Right-Pointing Angle Quotation Mark
        $trans[chr(156)] = '&oelig;';    // Latin Small Ligature OE
        $trans[chr(159)] = '&Yuml;';    // Latin Capital Letter Y With Diaeresis
        $trans['euro'] = '&euro;';    // euro currency symbol
        ksort($trans);

        foreach ($trans as $k => $v) {
            $text = str_replace($v, $k, $text);
        }

        // 3) remove <p>, <br/> ...
        $text = strip_tags($text);

        // 4) &amp; => & &quot; => '
        $text = html_entity_decode($text);

        // 5) remove Windows-1252 symbols like "TradeMark", "Euro"...
        $text = preg_replace('/[^(\x20-\x7F)]*/','', $text);

        // 6) remove double withspace
        $targets=array('\r\n','\n','\r','\t');
        $results=array(" "," "," ","");
        $text = str_replace($targets,$results,$text);

        // 6) remove double withspace
        $text = str_replace("  ", " ", $text);

        //XML compatible

        $text = str_replace("&", "and", $text);
        $text = str_replace("<", ".", $text);
        $text = str_replace(">", ".", $text);
        $text = str_replace("\\", "-", $text);
        $text = str_replace("/", "-", $text);


        if($withspace)
            $text = str_replace(" ", "", $text);

        return ($text);
    }

    /**
     * @param $string
     * @param int $length
     * @param string $pattern
     * @param string $method
     * @return string
     */
    static public function trimstr($string, $length = 25, $pattern = '...', $method = 'WORDS') {

        if (!is_numeric($length)) {
            $length = 25;
        }

        if (strlen($string) <= $length) {
            return rtrim($string) . $pattern;
        }

        $truncate = substr($string, 0, $length);

        if ($method != 'WORDS') {
            return rtrim($truncate) . $pattern;
        }

        if ($truncate[$length - 1] == ' ') {
            return rtrim($truncate) . $pattern;
        }
        // we got ' ' right where we want it

        $pos = strrpos($truncate, ' ');
        // lets find nearest right ' ' in the truncated string

        if (!$pos) {
            return $pattern;
        }
        // no ' ' (one word) or it resides at the very begining
        // of the string so the whole string goes to the toilet

        return rtrim(substr($truncate, 0, $pos)) . $pattern;
        // profit
    }

    /**
     * @param $name
     * @return mixed
     */
    static public function moveMishmashLetter($name) {
        $name = str_replace("Â", "", $name);
        return $name;
    }

    /**
     * @param $name
     * @return string
     */
    static public function generateSlug($name) {
        $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'Ð', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', '?', '?', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', '?', '?', 'L', 'l', 'N', 'n', 'N', 'n', 'N', 'n', '?', 'O', 'o', 'O', 'o', 'O', 'o', 'Œ', 'œ', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'Š', 'š', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Ÿ', 'Z', 'z', 'Z', 'z', 'Ž', 'ž', '?', 'ƒ', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', '?', '?', '?', '?', '?', '?');
        $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
        $slug = strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), str_replace($a, $b, $name)));
        return uniqid() . '-' . trim($slug, '-');
    }

    public function getName() {
        return 'formater';
    }

}

?>
