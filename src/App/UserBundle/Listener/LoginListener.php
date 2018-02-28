<?php

namespace App\UserBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Component\Routing\Router;

/**
 * Custom login listener.
 */
class LoginListener {

    /** @var \Symfony\Component\Security\Core\SecurityContext */
    private $securityContext;

    /** @var \Doctrine\ORM\EntityManager */
    private $em;
    private $router;
    protected $container;

    /**
     * Constructor
     * 
     * @param SecurityContext $securityContext
     * @param Doctrine        $doctrine
     */
    public function __construct(SecurityContext $securityContext, Doctrine $doctrine, Router $router,ContainerInterface $container) {
        $this->container = $container;
        $this->securityContext = $securityContext;
        $this->router = $router;
        $this->em = $doctrine->getManager();
    }

    /**
     * Do the magic.
     * 
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) {
        
        $user = $this->securityContext->getToken()->getUser();

        
        //    $user = $this->container->get("repo.user")->findOneById($user->getId());
        //$this->container->get('fos_user.user_manager')->updateUser($user);          
                                                              
        // if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
        // 	// user has just logged in
        // }
        // if ($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
        // 	// user has logged in using remember_me cookie
        // }
        // do some other magic here
        // ...
    }

}