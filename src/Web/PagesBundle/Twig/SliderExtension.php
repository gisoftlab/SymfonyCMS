<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace Web\PagesBundle\Twig;


use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Knp\Component\Pager\Paginator;


class SliderExtension extends \Twig_Extension
{
    
     protected $doctrine;
     protected $paginator;              
     protected $container;

     public function __construct(RegistryInterface $doctrine, Paginator $paginator,ContainerInterface $container)
    {
        $this->doctrine = $doctrine;
        $this->paginator = $paginator;        
        $this->container = $container;                   
    }           
             
    
    
    public function getFunctions()
    {
        return array(
//            new \Twig_SimpleFunction('checkCommentIsUser', array($this, 'checkCommentIsUser')),
        );
    }
    
    public function getFilters()
    {
        return array(
        );
    }                
    
 
    public function getName()
    {
        return 'web_slider_extension';
    }
    
}
