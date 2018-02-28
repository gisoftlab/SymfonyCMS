<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\CoreBundle\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use App\CoreBundle\Twig\CoreExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GsControllerListener {

    protected $extension;
    protected $container;

    /**
     * GsControllerListener constructor.
     * @param CoreExtension $extension
     * @param ContainerInterface|null $container
     */
    public function __construct(CoreExtension $extension, ContainerInterface $container = null) {
        $this->extension = $extension;
        $this->container = $container;
    }

    /**
     * onKernelController
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event) {

        // $this->container->get("service.counter")->CountVisit();

        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {

            $controllers = $event->getController();


            if (is_array($controllers)) {
                $controller = $controllers[0];

                if (is_object($controller) && method_exists($controller, 'preExecute')) {
                    $controller->preExecute();
                }
            }

            $this->extension->setController($controllers);
        }
    }

}
