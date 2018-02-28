<?php

namespace App\CoreBundle\Libraries\Utils;

use App\CoreBundle\Twig\FormaterExtension as formater;

class ViewHelper {

    /**
     * @param $text
     * @return string
     */
    static public function slugify($text) {
        return formater::slugify($text);
    }

    /**
     * @param $string
     * @param int $length
     * @param string $pattern
     * @param string $method
     * @return string
     */
    static public function trimstr($string, $length = 25, $pattern = '...', $method = 'WORDS') {
        return formater::trimstr($string, $length, $pattern, $method);        
    }

    /**
     * @param $name
     * @return mixed
     */
    static public function moveMishmashLetter($name){
         return formater::moveMishmashLetter($name);       
    }

    /**
     * @param $name
     * @return string
     */
    static public function generateSlug($name)
    {
         return formater::generateSlug($name);        
    }

}
