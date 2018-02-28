<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */
namespace App\FilesBundle\Twig;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\CounterBundle\Services\CounterServices as CounterServices;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\FilesBundle\Entity\File;
use App\FilesBundle\Entity\Thumbnail;

/**
 * Twig FilesExtension
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 */
class FilesExtension extends \Twig_Extension {

    protected $doctrine;
    protected static $context;
    protected static $container;

    public function __construct(RegistryInterface $doctrine, TokenStorageInterface $context, ContainerInterface $container) {
        $this->doctrine = $doctrine;
        self::$context = $context;
        self::$container = $container;
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('chackOldBrowser', array($this, 'chackOldBrowser')),
            new \Twig_SimpleFunction('getTypeName', array($this, 'getTypeName')),
            new \Twig_SimpleFunction('getImageSizeID', array($this, 'getImageSizeID')),
            new \Twig_SimpleFunction('getIconTitle', array($this, 'getIconTitle')),
            new \Twig_SimpleFunction('getImageTitle', array($this, 'getImageTitle')),
            new \Twig_SimpleFunction('getIconFromPath', array($this, 'getIconFromPath')),
            new \Twig_SimpleFunction('getImage', array($this, 'getImage')),
            new \Twig_SimpleFunction('isIcon', array($this, 'isIcon')),
            new \Twig_SimpleFunction('isIconProd', array($this, 'isIconProd')),
            new \Twig_SimpleFunction('hasIcon', array($this, 'hasIcon')),
            new \Twig_SimpleFunction('hasIconPromoted', array($this, 'hasIconPromoted')),
            new \Twig_SimpleFunction('getIcon', array($this, 'getIcon')),
            new \Twig_SimpleFunction('getIconTitle', array($this, 'getIconTitle')),
            new \Twig_SimpleFunction('thumbnail', array($this, 'thumbnail'),array('is_safe' => array('html'))),
        );
    }

    /**
    * GET User
    *
    * @return User
    */
    private static function getUser() {
        return self::$context->getToken()->getUser();
    }

    /**
    * chackOldBrowser
    *
    * @return bool
    */
    public static function chackOldBrowser() {

        $browser = self::$container->get("service.counter")->detectBrowser();

        if ($browser['name'] == 'mozilla') {
            $version = $browser['version'];
            $verArray = explode('.', $version);
            if (isset($verArray[0]) && $verArray[0] < 4) {
                return false;
            }
        } elseif ($browser['name'] == 'msie') {
            return false;
        }

        return true;
    }

     /**
    * getImageTitle 
    *
    * @param File $file 
    * @return string|false
    */
    public static function getImageTitle($file) {
        if ($file)
            return $file->getTitle();
        else
            return false;
    }

    /**
    * getImage
    *
    * @param File $file
    * @param string $name  
    * @return string|false
    */
    public static function getImage($file, $name = false) {
        if ($file)
            return self::getIconFromPath($file->getPath(), $name);
        else
            return false;
    }

     /**
    * getIconFromPath
    
    * @param string $path  
    * @param string $name    
    * @return string
    */
    public static function getIconFromPath($path, $name = false) {
        if ($name)
            return str_replace(Thumbnail::$ImageSizeOriginal, $name, $path);
        else
            return $path;
    }

    /**
    * getIconTitle
    *
    * @param Object $model 
    * @return string|false
    */
    public static function getIconTitle($model) {

       if(self::hasIcon($model)){
           $files = $model->getIcon();
            return $files->getTitle();
       }
        
        return false;
    }

    /**
    * getIcon
    *
    * @param Object $model 
    * @param string  $name 
    * @return string|false
    */
    function getIcon($model, $name = false) {

        if(self::hasIcon($model))
            return self::getImage($model->getIcon(), $name);
        
        return false;
    }

     /**
    * hasIcon
    *
    * @param Object $model     
    * @return bool
    */
    public static function hasIcon($model) {
        if ($model->getIcon())
            return true;
        
        return false;
    }
    
     /**
    * hasIconPromoted
    *
    * @param Object $model     
    * @return bool
    */
    public static function hasIconPromoted($model) {
        if ($model->getIconPromoted())
            return true;
        
        return false;
    }

     /**
    * isIcon
    *
    * @param Object $value     
    * @return bool
    */
    public static function isIcon($value) {
        if (!$value->getPage())
            return false;

        if (!$value->getPage()->getIcon())
            return false;

        if (!$value->getFile())
            return false;
        if ($value->getPage()->getIcon()->getId() == $value->getFile()->getId())
            return true;
    }
    
     /**
    * isIconProd
    *
    * @param Object $value     
    * @return bool
    */
      public static function isIconProd($value) {
        if (!$value->getProduct())
            return false;

        if (!$value->getProduct()->getIcon())
            return false;

        if (!$value->getFile())
            return false;
        if ($value->getProduct()->getIcon()->getId() == $value->getFile()->getId())
            return true;
    }

    /**
    * getTypeName
    *
    * @param string $name     
    * @return string
    */
    public static function getTypeName($name) {
        return File::getTypeName($name);
    }

    /**
    * getImageSizeID
    *
    * @param string $name     
    * @return int
    */
    public static function getImageSizeID($name) {
        return Thumbnail::getImageSizeID($name);
    }

     /**
    * thumbnail
    *
    * @param File $file     
    * @param string $name     
    * @param string $attr     
    * @return string|false
    */
    public function thumbnail($file, $name = false, $attr = null) {

        if ($file) {
            $attr["title"] = (!isset($attr["title"])) ? $file->getTitle() : $attr["title"];
            $attr["alt"] = (!isset($attr["alt"])) ? $file->getTitle() : $attr["alt"];
            $lineAttr = $this->buildTagLine($attr);

            return "<img src='" . self::getIconFromPath($file->getPath(), $name) . "'" . $lineAttr . " /> ";
        }

        return false;
    }

   /**
    * buildTagLine
    *
     * @param array $attr
     * @return string
     */
    private function buildTagLine(Array $attr) {
        $lineAttr = "";
        if ($attr)
            foreach ($attr as $key => $value) {
                $lineAttr .= " " . $key . "='" . $value . "'";
            }
        return $lineAttr;
    }

    public function getName() {
        return 'files_extension';
    }

}
