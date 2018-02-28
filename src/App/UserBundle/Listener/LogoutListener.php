<?php

namespace App\UserBundle\Listener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LogoutListener implements LogoutSuccessHandlerInterface {

  private $security;  
  protected $container;

    /**
     * LogoutListener constructor.
     * @param SecurityContext $security
     * @param ContainerInterface $container
     */
  public function __construct(SecurityContext $security,ContainerInterface $container) {
    $this->security = $security;
    $this->container = $container;
  }

  
  public function onLogoutSuccess(Request $request) {
     //$user = $this->security->getToken()->getUser();
                  
     return new RedirectResponse($this->container->get('router')->generate($this->container->get('session')->get('_locale').'_homepage'));
        
  }
}