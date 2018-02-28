<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace Web\PagesBundle\Twig;


use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class PageExtension extends \Twig_Extension
{
         
     protected $container;

     public function __construct(ContainerInterface $container)
    {    
        $this->container = $container;                   
    }           
             
    
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('isMenuActive', array($this, 'isMenuActive')),
            new \Twig_SimpleFunction('isCategoryActive', array($this, 'isCategoryActive')),
            new \Twig_SimpleFunction('getSlug', array($this, 'getSlug')),
        );
    }
    
    public function getFilters()
    {
        return array(            
//            'mostVotedComment' => new Twig_Filter_Method($this, 'mostVotedCommentFilter'),            
//            'injectTags' => new Twig_Filter_Method($this, 'injectTagsFilter'),
//            'showFirstImgFromPost' => new Twig_Filter_Method($this, 'showFirstImgFromPostFilter'),                                    
        );
    }                
    
    
    public function getSlug(){
         $uri = $_SERVER["PATH_INFO"];
         $uri = explode("/", $uri);
         return  isset($uri[1])?$uri[1]:null;  
    }
    
    public function isMenuActive($menu,$page)
    {               
        $mslug = $menu->getSlug();
        
        if ($page) {
            if ($page->getSlug() == $mslug) {                   
                return "current";
            }else if ($parent = $page->getParent()){
                  if ($parent->getSlug() == $mslug)     
                      return "current";
            }                        
        }                         
         return "";                                    
    }
    
     public function isCategoryActive($category,$page)
    {
         
        $mslug = $category->getSlug();
        
        if ($page) {
            if ($page->getSlug() == $mslug) {                   
                return "active";
            }else if ($parent = $page->getParent()){
                  if ($parent->getSlug() == $mslug)     
                      return "active";
            }                        
        }                         
         return "";        
         
    }
    
    

    public function getName()
    {
        return 'web_pages_extension';
    }
    
}
