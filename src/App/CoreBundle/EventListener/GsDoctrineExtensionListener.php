<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GsDoctrineExtensionListener implements ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * setContainer
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    /**
     * onLateKernelRequest
     * @param GetResponseEvent $event
     */
    public function onLateKernelRequest(GetResponseEvent $event) {
        $translatable = $this->container->get('gedmo.listener.translatable');
        $translatable->setTranslatableLocale($event->getRequest()->getLocale());
    }

    /**
     * onKernelRequest
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event) {
        $securityContext = $this->container->get('security.authorization_checker', ContainerInterface::NULL_ON_INVALID_REFERENCE);
        $tokenStorage = $this->container->get('security.token_storage');
        if (null !== $securityContext && null !== $tokenStorage->getToken() && $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $loggable = $this->container->get('gedmo.listener.loggable');
            $loggable->setUsername($tokenStorage->getToken()->getUsername());
        }
    }

}
