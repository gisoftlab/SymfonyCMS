<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */
namespace Web\NewsletterBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Web\NewsletterBundle\Form\NewsletterEmailType;
use App\CoreBundle\Controller\BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\NewsletterBundle\Entity\NewsletterEmail;

/**
 * Frontend NewsletterEmail controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>
 */
class NewsletterEmailController extends BaseController
{
    protected $namespace = 'WebNewsletterBundle';
    protected $module = 'NewsletterEmail';
    protected $fieldName = "NewsletterEmail";
    protected $redirectShow = "_newsletter_show";

    /**
     * show action
     *
     * @param Request $request
     * @return Response
     */
    public function showAction(Request $request) {

        $form = $this->createForm(NewsletterEmailType::class);
        if ($request->get("newsletterEmail")) {
            $form->handleReque($request);
            if ($form->isValid()) {

            }
        }

        return $this->render(
            'WebNewsletterBundle:NewsletterEmail:sent.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * sent action
     *
     * @param Request $request
     * @return Response
     */
    public function sentAction(Request $request) {

        $form = $this->createForm(NewsletterEmailType::class);
        if ($request->get("newsletterEmail")) {
            $form->handleReque($request);
            if ($form->isValid()) {

                $formEmail = $form->getData();
                if (isset($formEmail["email"])) {
                    $this->get("repo.newsletterEmail")->saveNews($formEmail["email"]);
                    $confirm["note"] = "ok";
                    $confirm["msg"] = "Email został dodany";
                } else {
                    $confirm["note"] = "error";
                    $confirm["msg"] = "Email nie został dodany";
                }
            }
        }

        $confirm["html"] = $this->render(
            'WebNewsletterBundle:NewsletterEmail:sent.html.twig',
            array(
                'form' => $form->createView(),
                "note" => "info",
                "msg" => "",
            )
        );
        
        return new Response(json_encode($confirm));
    }
}
