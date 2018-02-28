<?php

namespace App\CoreBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use App\CoreBundle\Libraries\Utils\TextFunctions;


/**
 * Twig Extension for Formater support.
 *
 * @author damian <info@gisoft.pl>
 */
class TextFunctionsExtension extends \Twig_Extension {

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
            new \Twig_SimpleFunction('truncateText', array($this, 'truncateText')),
            new \Twig_SimpleFunction('getStartLetterFromText', array($this, 'getStartLetterFromText')),
            new \Twig_SimpleFunction('formatSearchText', array($this, 'formatSearchText')),
            new \Twig_SimpleFunction('removeDuplicateParagraphs', array($this, 'removeDuplicateParagraphs')),
            new \Twig_SimpleFunction('htmlParagraphs', array($this, 'htmlParagraphs')),
            new \Twig_SimpleFunction('compareByLength', array($this, 'compareByLength')),
            new \Twig_SimpleFunction('autolinkText', array($this, 'autolinkText')),
            new \Twig_SimpleFunction('freeTextWhereClause', array($this, 'freeTextWhereClause')),
            new \Twig_SimpleFunction('replaceSmartQuotesDashesAndElipses', array($this, 'replaceSmartQuotesDashesAndElipses')),
            new \Twig_SimpleFunction('translateDiacritics', array($this, 'translateDiacritics')),
            new \Twig_SimpleFunction('hex2Unicode', array($this, 'hex2Unicode')),
            new \Twig_SimpleFunction('diacriticTranslationArray', array($this, 'diacriticTranslationArray')),            
        );
    }


    /**
     * @param $text
     * @param int $numberOfChars
     * @param string $suffix
     * @return string
     */
    static public function truncateText($text, $numberOfChars = 50, $suffix = "...") {
        return TextFunctions::truncateText($text, $numberOfChars, $suffix);        
    }

    /**
     * @param $text
     * @return mixed|string
     */
    static public function getStartLetterFromText($text) {
        return TextFunctions::getStartLetterFromText($text);        
    }

    /**
     * @param string $text
     * @return mixed|string
     */
    static function formatSearchText($text = '') {
        return TextFunctions::formatSearchText($text);        
    }

    /**
     * @param $text
     * @return mixed
     */
    static public function removeDuplicateParagraphs($text) {
       return TextFunctions::removeDuplicateParagraphs($text);        
    }

    /**
     * @param $text
     * @param string $tag
     * @return mixed|string
     */
    static public function htmlParagraphs($text, $tag = 'br') {
      return TextFunctions::htmlParagraphs($text,$tag);        
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    static public function compareByLength($a, $b) {
         return TextFunctions::compareByLength($a,$b);        
    }

    /**
     * Inserts links into text
     *
     * @param $text
     * @param array $autolinks
     * @return string
     */
    static public function autolinkText($text, $autolinks = array()) {
       return TextFunctions::autolinkText($text,$autolinks);        
    }

    /**
     * @param $fieldname
     * @param $searchtext
     * @return string
     */
    static public function freeTextWhereClause($fieldname, $searchtext) {
       return TextFunctions::freeTextWhereClause($fieldname,$searchtext);        
    }

    /**
     * @param $text
     * @return mixed
     */
    static public function replaceSmartQuotesDashesAndElipses($text) {
        return TextFunctions::replaceSmartQuotesDashesAndElipses($text);        
    }

    /**
     * @param $text
     * @return mixed|string
     */
    static public function translateDiacritics($text) {
        return TextFunctions::translateDiacritics($text);        
    }

    /**
     * @param $hexvalue
     * @return string
     */
    static public function hex2Unicode($hexvalue) {
        return TextFunctions::hex2Unicode($hexvalue);
    }

    /**
     * @return array
     */
    static public function diacriticTranslationArray() {
        return TextFunctions::diacriticTranslationArray();
    }

    public function getName() {
        return 'text_functions';
    }

}

?>
