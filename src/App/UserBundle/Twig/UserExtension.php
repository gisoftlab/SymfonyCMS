<?php

namespace App\UserBundle\Twig;


use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\UserBundle\Entity\CompanyType as Type;

class UserExtension extends \Twig_Extension {

    protected $doctrine;
    protected static $context;

    public function __construct(RegistryInterface $doctrine, TokenStorageInterface $context) {
        $this->doctrine = $doctrine;
        self::$context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('isAdmin', array($this, 'isAdmin')),
            new \Twig_SimpleFunction('checkPrivilage', array($this, 'checkPrivilage')),
            new \Twig_SimpleFunction('isCooperator', array($this, 'isCooperator')),
            new \Twig_SimpleFunction('isAdmin', array($this, 'isAdmin')),
            new \Twig_SimpleFunction('isUser', array($this, 'isUser')),
            new \Twig_SimpleFunction('isLogin', array($this, 'isLogin')),
        );
    }

    /**
     * @return mixed
     */
    private static function getUser(){
        return self::$context->getToken()->getUser();
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function checkPrivilage($name){
        return self::getUser()->checkPrivilage($name);
    }

    /**
     * @return mixed
     */
    public static function isAdmin(){
        return self::getUser()->isAdmin();        
    }

    /**
     * @return mixed
     */
    public static function isCooperator(){        
       return self::getUser()->isCooperator();                 
    }

    /**
     * @return bool
     */
    public static function isLogin(){
        if (is_object(self::getUser())) 
           return true;
       else     
           return false;
    }

    /**
     * @return mixed
     */
    public static function isUser()
    {
       return self::getUser()->isUsers();                   
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'user_extension';
    }
}
