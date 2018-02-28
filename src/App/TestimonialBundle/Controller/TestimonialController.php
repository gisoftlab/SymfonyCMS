<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */
namespace App\TestimonialBundle\Controller;

use App\PagesBundle\Entity\Metatag;
use App\TestimonialBundle\Entity\Testimonial;
use App\UserBundle\Entity\EmailReporting;
use Swift_Message;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\CoreBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use App\TestimonialBundle\Form\TestimonialType;

/**
 * Backend TestimonialEmail controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>
 */
class TestimonialController extends BaseController
{
    protected $namespace = 'AppTestimonialBundle';
    protected $module = 'Testimonial';
    protected $fieldName = "Opinia";
    protected $redirectShow = "_testimonial_show";

    /**
     * display  index Action
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $page = ($request->get('page')) ? $request->get('page') : 1;
        $sidx = ($request->get('sort')) ? $request->get('sort') : '';
        $sort = ($request->get('direction')) ? $request->get('direction') : 'desc';

        $paginator = $this->get('knp_paginator');
        $pager = $this->getRepo()->getList($paginator, $page, 50, $sidx, $sort);


        return $this->renderTpl(
            'index',
            array(
                'pager' => $pager,
            )
        );
    }

    /**
     * display  edit  Testimonial Action
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        if (!($model = $this->getRepo()->findOneBy(array('id' => $id)))) {
            throw new NotFoundHttpException('The '.$this->fieldName.' does not exist.');
        }

        $form = $this->createForm(TestimonialType::class, $model);
        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                try {
                    $this->getRepo()->save($model);

                    $this->setFlash('ok', $this->fieldName.' poprawiony poprawnie');
                } catch (Exception $ex) {
                    $this->setFlash('error', 'Błąd podczas poprawiania '.$this->fieldName);
                }

                return new RedirectResponse($this->generateUrl($this->redirectShow));
            }
        }

        return $this->renderTpl(
            'edit',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * display  new  Testimonial Action
     *
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {

        $model = new Metatag();
        $form = $this->createForm(TestimonialType::class, $model);
        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                try {
                    $model->addTranslation(new \App\PagesBundle\Entity\MetatagTranslation('en', 'title', 'title'));
                    $model->addTranslation(
                        new \App\PagesBundle\Entity\MetatagTranslation('en', 'keywords', 'keywords')
                    );
                    $model->addTranslation(
                        new \App\PagesBundle\Entity\MetatagTranslation('en', 'description', 'description')
                    );

                    $this->getRepo()->save($model);

                    $this->setFlash('ok', $this->fieldName.' poprawiona poprawnie');
                } catch (Exception $ex) {
                    $this->setFlash('error', 'Błąd podczas poprawiania '.$this->fieldName);
                }

                return new RedirectResponse($this->generateUrl($this->redirectShow));
            }
        }

        return $this->renderTpl(
            'new',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * display  new  Testimonial Action
     *
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction($id)
    {

        if (!($model = $this->getRepo()->findOneBy(array('id' => $id)))) {
            throw new NotFoundHttpException('The '.$this->fieldName.' does not exist.');
        }

        try {
            $this->getRepo()->delete($model);

            $this->setFlash('ok', $this->fieldName.' poprawnie usunięta');
        } catch (Exception $ex) {
            $this->setFlash('error', 'Błąd podczas usuwania '.$this->fieldName);
        }

        return new RedirectResponse($this->generateUrl($this->redirectShow));
    }


    /**
     * display  new  Testimonial Action
     *
     * @param $id
     * @return RedirectResponse
     */
    public function sendResponseAction($id)
    {

        /**
         * @var Testimonial $model
         */
        if (!($model = $this->getRepo()->findOneBy(array('id' => $id)))) {
            throw new NotFoundHttpException('The '.$this->fieldName.' does not exist.');
        }

        try {
            $bodyHtml = $this->renderView('WebWebBundle:Emails:_email_response.html.twig', array(
                'model' => $model
            ));

            $mailerSender = $this->getParameter("mailer_sender");

            $message = Swift_Message::newInstance()
                ->setSubject('Opinia wystawiona na Orient-Med została odrzucona')
                ->setFrom($mailerSender["email"])
                ->setTo($model->getEmail())
                ->setBody($bodyHtml, 'text/html')
                //->addPart($bodyText,'text/plain')
            ;

            try{
                $this->get('mailer')->send($message);
                $this->get("repo.emailReporting")->saveRaport($message,EmailReporting::TYPE_RESPONSE);

            }catch(\Exception $ex) {
                $this->get("repo.emailReporting")->saveRaport($message,EmailReporting::TYPE_RESPONSE,$ex->getMessage());

            }

            $this->setFlash('ok','Odmowna odpowiedz zostala poprawnie wyslana.');
        } catch (Exception $ex) {
            $this->setFlash('error', 'Błąd podczas wysylania wiadomosci '.$this->fieldName);
        }

        return new RedirectResponse($this->generateUrl($this->redirectShow));
    }

    /**
     *  Published Action
     *
     * @param Request $request
     * @param $id
     * @param $value
     * @return RedirectResponse
     */
    public function publishedAction(Request $request, $id, $value)
    {
        /**
         * @var Testimonial $model
         */
        if (!($model = $this->getRepo()->findOneBy(array('id' => $id)))) {
            throw new NotFoundHttpException('The opinia does not exist.');
        }

        try {
            $model->setPublished($value);
            $this->getRepo()->save($model);

            $this->setFlash('ok', 'Oinia opublikowana');
        } catch (Exception $ex) {
            $this->setFlash('error', 'Błąd podczas publikowania opinii');
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * multi Action
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function multiAction(Request $request)
    {

        $multiCheckBox = $request->request->get("multiCheckBox");

        try {

            $this->getRepo()->multiDelete($multiCheckBox);

            $this->setFlash('ok', 'Wybrane opinie zostały poprawnie usunięte');
        } catch (Exception $ex) {
            $this->setFlash('error', 'Błąd podczas usuwania opinii');
        }

        return new RedirectResponse($this->generateUrl($this->redirectShow, $this->mergeParams()));
    }

}
