<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */
namespace App\NewsletterBundle\Controller;

use App\PagesBundle\Entity\Metatag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\PagesBundle\Form\Type\Metatag as MetatagType;
use App\CoreBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Backend NewsletterEmail controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>
 */
class NewsletterEmailController extends BaseController
{
    protected $namespace = 'AppNewsletterBundle';
    protected $module = 'NewsletterEmail';
    protected $fieldName = "Newsletter";
    protected $redirectShow = "_newsletter_show";

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
        $sort = ($request->get('direction')) ? $request->get('direction') : 'asc';

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
     * display  edit  newsletter Action
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        if (!($model = $this->getRepo()->findOneBy(array('id' => $id)))) {
            throw new NotFoundHttpException('The '.$this->fieldName.' does not exist.');
        }

        $form = $this->createForm(MetatagType::class, $model);
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
     * display  new  newsletter Action
     *
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {

        $model = new Metatag();
        $form = $this->createForm(MetatagType::class, $model);
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

                    $this->setFlash('ok', $this->fieldName.' poprawiony poprawnie');
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
     * display  new  newsletter Action
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

            $this->setFlash('ok', $this->fieldName.' poprawnie usunięty');
        } catch (Exception $ex) {
            $this->setFlash('error', 'Błąd podczas usuwania '.$this->fieldName);
        }

        return new RedirectResponse($this->generateUrl($this->redirectShow));
    }

}
