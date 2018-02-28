<?php

namespace App\SettingsBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use App\SettingsBundle\Entity\Settings;

class SettingsExtension extends \Twig_Extension
{
    
     protected $setting;                 
     protected $container;

    public function __construct(ContainerInterface $container){        
        $this->container = $container;
        $this->setting =  $this->container->get("service.settings");                                
    }
    
    public function getSetting(){
        return $this->setting;
    }


    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('getSettingByName', array($this, 'getSettingByName')),
            new \Twig_SimpleFunction('showSettingSlogan', array($this, 'showSettingSlogan')),
            new \Twig_SimpleFunction('showSettingCompanyName', array($this, 'showSettingCompanyName')),
            new \Twig_SimpleFunction('showSettingFirstName', array($this, 'showSettingFirstName')),
            new \Twig_SimpleFunction('showSettingLastName', array($this, 'showSettingLastName')),
            new \Twig_SimpleFunction('showSettingEmailMain', array($this, 'showSettingEmailMain')),
            new \Twig_SimpleFunction('showSettingEmail', array($this, 'showSettingEmail')),
            new \Twig_SimpleFunction('showSettingPhone', array($this, 'showSettingPhone')),
            new \Twig_SimpleFunction('showSettingCulture', array($this, 'showSettingCulture')),
            new \Twig_SimpleFunction('showSettingLang', array($this, 'showSettingLang')),
            new \Twig_SimpleFunction('showSettingCompanyHost', array($this, 'showSettingCompanyHost')),
        );
    }

    /**
     * @param $name
     * @return bool
     */
    public function getSettingByName($name) {
        return $this->getSetting()->get($name);
    }

    /**
     * @return bool
     */
    public function Slogan() {
        return $this->getSettingByName(Settings::SET_SLOGAN);
    }

    /**
     * @return bool
     */
    public function showSettingCompanyName() {        
        return $this->getSettingByName(Settings::SET_COMPANY_NAME);        
    }

    /**
     * @return bool
     */
    public function showSettingFirstName() {
        return $this->getSettingByName(Settings::SET_FIRST_NAME);                
    }

    /**
     * @return bool
     */
    public function showSettingLastName() {
        return $this->getSettingByName(Settings::SET_LAST_NAME);                        
    }

    /**
     * @return bool
     */
    public function showSettingEmailMain() {
        return $this->getSettingByName(Settings::SET_EMAIL_MAIN);                              
    }

    /**
     * @return bool
     */
    public function showSettingEmail() {
        return $this->getSettingByName(Settings::SET_EMAIL);                        
    }

    /**
     * @return bool
     */
    public function showSettingPhone() {
        return $this->getSettingByName(Settings::SET_PHONE);        
    }

    /**
     * @return bool
     */
    public function showSettingCulture() {
        return $this->getSettingByName(Settings::SET_CULTURE);                
    }

    /**
     * @return bool
     */
    public function showSettingLang() {
        return $this->getSettingByName(Settings::SET_LANG);                        
    }

    /**
     * @return bool
     */
    public function showSettingCompanyHost() {
        return $this->getSettingByName(Settings::SET_COMPANY_HOST);                        
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'settings_extension';
    }
    
}
