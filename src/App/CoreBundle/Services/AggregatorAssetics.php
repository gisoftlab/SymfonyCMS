<?php

namespace App\CoreBundle\Services;

use App\CoreBundle\Utils\AsseticConverter;

class AggregatorAssetics {

    /**
     * @var AggregatorAssetics The reference to *Singleton* instance of this class
     */
    private static $instance;

    /**
     * @var array $aggragateJs
     */
    private static $aggragateJs;

    /**
     * @var array $aggragateJsInline
     */
    private static $aggragateJsInline;

    /**
     * @var array $aggragateCss
     */
    private static $aggragateCss;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return AggregatorAssetics The *Singleton* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * ADD Javascript ASSET
     *
     * @param array|string $assets
     */
    public function addJs($assets){
        
        if(is_array($assets)){
            foreach ($assets as $index => $realPath) {
                self::$aggragateJs[$realPath] = $realPath;
            }
        }else{
            if($assets)
                self::$aggragateJs[$assets] = $assets;
        }
    }

    /**
     * GET List of Javascript
     *
     * @return array
     */
    public function getListOfJs(){
        return self::$aggragateJs;
    }

    /**
     * ADD CSS ASSET
     *
     * @param array|string $assets
     */
    public function addCss($assets){
        if(is_array($assets)){
            foreach ($assets as $index => $realPath) {
                self::$aggragateJs[$realPath] = $realPath;
            }
        }else{
            if($assets)
                self::$aggragateJs[$assets] = $assets;
        }
    }

    /**
     * GET List of CSS
     *
     * @return array
     */
    public function getListOfCss(){
        return self::$aggragateCss;
    }


    /**
     * ADD Javascript Inline ASSET
     *
     * @param array|string $assets
     */
    public function addJsInline($assets){
        if(is_array($assets)){
            foreach ($assets as $index => $cource) {
                $cource = AsseticConverter::convertToLine($cource);
                self::$aggragateJsInline[$cource] = $cource;
            }
        }else{
            $cource = AsseticConverter::convertToLine($assets);
            self::$aggragateJsInline[$cource] = $cource;
        }
    }

    /**
     * GET List of CSS
     *
     * @return array
     */
    public function getListOfInlineJs(){
        return self::$aggragateJsInline;
    }

}
