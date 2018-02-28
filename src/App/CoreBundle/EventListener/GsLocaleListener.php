<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GsLocaleListener implements EventSubscriberInterface {

    private $defaultLocale;

    /**
     * GsLocaleListener constructor.
     * @param string $defaultLocale
     */
    public function __construct($defaultLocale = 'en') {
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * onKernelRequest
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event) {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
        } else {
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
    }

    /**
     * getSubscribedEvents
     * @return array
     */
    static public function getSubscribedEvents() {
        return array(
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 17)),
        );
    }

}

?>