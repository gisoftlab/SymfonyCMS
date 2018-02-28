<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace Web\ProductBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\PagesBundle\Form\Type\Page as PageType;
use App\PagesBundle\Twig\UrlExtension as uri;
use App\CoreBundle\Controller\BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Web\ProductBundle\Form\OrderType;

/**
 * Frontend Cart controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 */
class CartController extends BaseController {

    protected $namespace = 'WebProductBundle';
    protected $module = 'Cart';
    protected $fieldName = "Strony";
    protected $redirectShow = "_c_show";

    public function showAction(Request $request) {

        $lang = $request->getLocale();
        $model = $this->module;
        $template = "show";

        $data = array(
            'items' => $this->get("product.order")->get(),
        );

        return $this->renderEsi('WebProductBundle:' . $model . ':' . $template . '.html.twig', $data);
    }

    public function rentAction(Request $request) {

        $model = $this->module;
        $template = "show";

        $items = $this->get("product.order")->get();

        $bodyHtml = $this->renderView('WebWebBundle:Emails:_email_orderCart.html.twig', array(
            'items' => $items,
        ));

        $mailerSender = $this->container->getParameter("mailer_sender");

        $message = \Swift_Message::newInstance()
                ->setContentType("text/html")
                ->setSubject('Zamówienie z wypożyczalni Torhen Koszyk')
                ->setFrom($mailerSender["email"])
                ->setTo($this->get("repo.settings")->getMainEmail())
                ->setBody($bodyHtml);


        $this->get("repo.productOrders")->saveOrderCart($items);
        $this->get('mailer')->send($message);
        $this->get("product.order")->clear();

        $this->get('session')->getFlashBag()->set('ok', 'Zamówienie zostało wysłane.');



        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    public function clearAction(Request $request) {

        $model = $this->module;
        $template = "show";

        $this->get("product.order")->clear();

        $this->get('session')->getFlashBag()->set('ok', 'Koszyk został wyczyszczony.');

        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

}
