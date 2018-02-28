<?php

namespace App\UserBundle\Listener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Listener responsible to change the redirection at the end of the password resetting
 */
class PasswordResettingListener implements EventSubscriberInterface {
    private $router;

    public function __construct(UrlGeneratorInterface $router) {
        $this->router = $router;
    }

    public static function getSubscribedEvents() {
        return array(
            FOSUserEvents::RESETTING_RESET_SUCCESS => 'onPasswordResettingSuccess',
        );
    }

    public function onPasswordResettingSuccess(FormEvent $event) {
        $request = $event->getRequest();

        $locale = $request->getSession()->get('_locale');
        $url = $this->router->generate($locale . '_homepage');
        $event->setResponse(new RedirectResponse($url));
    }
}
