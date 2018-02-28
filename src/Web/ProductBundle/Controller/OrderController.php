<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace Web\ProductBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use App\CoreBundle\Controller\BaseController;
use Web\ProductBundle\Form\OrderType;

/**
 * Frontend Order controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 */
class OrderController extends BaseController {

    protected $namespace = 'WebProductBundle';
    protected $module = 'Order';
    protected $fieldName = "Strony";
    protected $redirectShow = "_order_show";

    public function showAction(Request $request, $id) {

        $model = $this->module;
        $template = "show";

        $form = $this->createForm(OrderType::class);
        $form->setData(array("productId" => $id));

        $data = array(
            'form' => $form->createView(),
            'id' => $id,
            'msg' => null,
            'request' => $request,
        );

        return $this->render('WebProductBundle:' . $model . ':' . $template . '.html.twig', $data);
    }

    public function saveOrderAction(Request $request) {

        $model = $this->module;
        $template = "show";
        $msg = null;

        $form = $this->createForm(OrderType::class);
        if ('POST' == $request->getMethod()) {
            $form->handleReque($request);
            if ($form->isValid()) {

                $data = $form->getData();
                $product = $this->get("repo.product")->findOneBy(array('id' => $data["productId"]));

                $bodyHtml = $this->renderView('WebWebBundle:Emails:_email_orderItem.html.twig', array(
                    'data' => $data,
                    'product' => $product
                ));

                $mailerSender = $this->container->getParameter("mailer_sender");

                $message = \Swift_Message::newInstance()
                        ->setContentType("text/html")
                        ->setSubject('Zamówienie z wypożyczalni Torhen ' . $data["name"])
                        ->setFrom($mailerSender["email"])
                        ->setTo($this->get("repo.settings")->getMainEmail())
                        ->setBody($bodyHtml);
                try {
                    $this->get("repo.productOrders")->saveOrder($data);
                    $this->get('mailer')->send($message);
                    $msg["ok"] = "Zamowienie zostało wysłane";
                    $form = $this->createForm(OrderType::class);
                } catch (Exception $ex) {
                    $msg["error"] = "Błąd podczas wysyłania zamówienia";
                }
            }
        }

        $data = array(
            'form' => $form->createView(),
            'msg' => $msg,
            'request' => $request,
        );

        return $this->render('WebProductBundle:' . $model . ':' . $template . '.html.twig', $data);
    }

    public function saveCartAction(Request $request) {

        $model = $this->module;
        $template = "show";
        $msg = null;

        $form = $this->createForm(OrderType::class);
        if ('POST' == $request->getMethod()) {
            $form->handleReque($request);
            if ($form->isValid()) {

                $data = $form->getData();
                $product = $this->get("repo.product")->findOneBy(array('id' => $data["productId"]));

                try {
                    $this->get("product.order")->addProduct($data, $product);
                    $msg["ok"] = "Zamowienie zostało dodane do koszyka";
                    $form = $this->createForm(OrderType::class);
                } catch (Exception $ex) {
                    $msg["error"] = "Błąd podczas dodawania do koszyka";
                }
            }
        }

        $data = array(
            'form' => $form->createView(),
            'msg' => $msg,
            'request' => $request,
        );

        return $this->render('WebProductBundle:' . $model . ':' . $template . '.html.twig', $data);
    }

}
