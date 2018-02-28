<?php

namespace App\CoreBundle\Libraries\Utils;

use Symfony\Component\Yaml\Yaml;

class ConfigHelper {

    const PATH_CONFIG = "/../app/config/";
    const ENV_DEV = "dev";
    const ENV_PROD = "prod";
    const ENV_ALL = "all";    
    
    const CONFIG_SETTINGS = "settings";
    const CONFIG_COUNTER = "counter_blocked";
    const CONFIG_GOOGLE = "google";
    const CONFIG_FACEBOOK = "facebook";
    const CONFIG_PAYPAL = "paypal";
    const CONFIG_TINYMCE = "tinymce";
    
    private $configName = "";
    private $pathRoot = "";    
    private $env = "";
    private $config = "";

    /**
     * ConfigHelper constructor.
     * @param string $configName
     * @param string $env
     */
    public function __construct($configName = "settings", $env = "") {
        $this->configName = $configName;
        $this->env = (isset($_SERVER["SCRIPT_NAME"]) && strpos($_SERVER["SCRIPT_NAME"], self::ENV_DEV) !== false)?self::ENV_DEV:self::ENV_PROD;
        if($env && (self::ENV_DEV == $env || self::ENV_PROD == $env)){
            $this->env = $env;
        }
                
        $this->pathRoot = isset($_SERVER["DOCUMENT_ROOT"])?$_SERVER["DOCUMENT_ROOT"]:"";
        $this->pathConfig = $this->pathRoot .self::PATH_CONFIG;
    }

    /**
     * @param string $configName
     * @param string $env
     * @return ConfigHelper
     */
    public static function getInstance($configName = self::CONFIG_SETTINGS, $env = "") {
        return new ConfigHelper($configName,$env);
    }

    /**
     * @return string
     */
    public function geConfRoot(){
        return $this->pathConfig;        
    }

    /**
     * @return array|string
     */
    public function getWebRoot(){
        return $this->pathRoot;        
    }

    /**
     * @return string
     */
    public function getAppRoot(){
        return $this->pathRoot.'/../';        
    }

    /**
     * @return array|bool|mixed|string
     */
    public function getConfig() {
        
        $config_env = array();
        try{
            if(file_exists($this->pathConfig.$this->configName.'.yml')){
                $this->config = Yaml::parse(file_get_contents($this->pathConfig.$this->configName.'.yml'));
                $config_env   =  isset($this->config[$this->env])?$this->config[$this->env]:$this->config;
                $config_all   =  isset($this->config[self::ENV_ALL])?$this->config[self::ENV_ALL]:false;
                if($config_all)
                    return array_merge($config_env,$config_all);
            }
            
            return $config_env;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * @param bool $name
     * @param bool $value
     * @return array|bool|mixed|string
     */
    public function get($name = false, $value = false) {
         $config = $this->getConfig();
         
         if(!$config)
             return false;
         
        if (!$name){
            return $config;
        }else{
            if($value){
                if($temp =  (isset($config[$name])) ? $config[$name] : false){
                    return (isset($temp[$value])) ? $temp[$value] : false;
                }
                
                return false;
            }else{
                return (isset($config[$name])) ? $config[$name] : false;
            }
        }
            
    }
}
