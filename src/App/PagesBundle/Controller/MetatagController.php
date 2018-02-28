<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */
namespace App\PagesBundle\Controller;

use App\PagesBundle\Entity\Metatag;
use App\PagesBundle\Entity\MetatagTranslation;
use App\SettingsBundle\Repository\SettingsRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\PagesBundle\Form\MetatagType;
use App\CoreBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Backend Metatag controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>
 */
class MetatagController extends BaseController
{
    protected $namespace = 'AppPagesBundle';
    protected $module = 'Metatag';
    protected $fieldName = "Metatag";
    protected $redirectShow = "_metatag_show";

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
                'languages' => $this->get("repo.languages")->getLangLst(),
                'lang' => $this->get("repo.settings")->getLang(),
            )
        );
    }

    /**
     * display  edit Action
     *
     * @param Request $request
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        if (!($model = $this->getRepo()->findOneBy(array('id' => $id)))) {
            throw new NotFoundHttpException('The '.$this->fieldName.' does not exist.');
        }

        $form = $this->createForm(MetatagType::class, $model);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                try {
                    $this->getRepo()->save($model);
                    $this->setFlash('ok', $this->fieldName.' poprawiony poprawnie');
                } catch (\Exception $ex) {
                    $this->setFlash('error', 'Błąd podczas poprawiania '.$this->fieldName);
                }

                return new RedirectResponse($this->generateUrl($this->redirectShow));
            }
        }

        return $this->render(
            'AppPagesBundle:Metatag:edit.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * display  new Action
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {

        $model = new Metatag();
        $form = $this->createForm(MetatagType::class, $model);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                try {
                    /**
                     * @var SettingsRepository
                     */
                    $lang = $this->get("repo.settings")->getLang();
                    $model->addTranslation(new MetatagTranslation($lang, 'title', 'title'));
                    $model->addTranslation(new MetatagTranslation($lang, 'keywords', 'keywords'));
                    $model->addTranslation(new MetatagTranslation($lang, 'description', 'description'));

                    $this->getRepo()->save($model);

                    $this->setFlash('ok', $this->fieldName.' poprawiony poprawnie');
                } catch (Exception $ex) {
                    $this->setFlash('error', 'Błąd podczas poprawiania '.$this->fieldName);
                }

                return new RedirectResponse($this->generateUrl($this->redirectShow));
            }
        }

        return $this->render(
            'AppPagesBundle:Metatag:new.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * display  quickEdit Action
     *
     * @param Request $request
     * @param int $id
     * @param int $quick
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function quickEditAction(Request $request, $id, $quick)
    {
        /**
         * @var Metatag $model
         */
        if (!($model = $this->getRepo()->findOneBy(array('id' => $id)))) {
            throw new NotFoundHttpException('The Metatags does not exist.');
        }

        $confirm["id"] = $id;

        $form = $this->createForm(MetatagType::class, $model);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                try {
                    $this->getRepo()->save($model);

                    $confirm["note"] = "ok";
                    $confirm["msg"] = "Metatagi poprawiona pomyślnie";
                    $confirm["title"] = $model->getTitle();
                    $confirm["quick"] = true;
                } catch (Exception $ex) {
                    $confirm["note"] = "error";
                    $confirm["msg"] = "Wystąpił błąd podczas edycji";
                }
            }
        }

        $confirm["html"] = $this->renderView(
            'AppPagesBundle:Metatag:_quickForm.html.twig',
            array(
                'form' => $form->createView(),
                'id' => $id,
                'quick' => $quick,
            )
        );

        return new Response(json_encode($confirm));
    }

    /**
     * display  translate Action
     *
     * @param Request $request
     * @param $id
     * @param $quick
     * @param $culture
     * @return Response
     */
    public function translateAction(Request $request, $id, $quick, $culture)
    {
        /**
         * @var Metatag $model
         */
        if (!($model = $this->getRepo()->retrivateMetatagByLang($id, $culture))) {
            throw new NotFoundHttpException('The Metatag does not exist.');
        }

        $confirm["id"] = $id;
        $form = $this->createForm(MetatagType::class, $model);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                try {
                    $model->setTranslatableLocale($culture);
                    $this->getRepo()->save($model);

                    $confirm["note"] = "ok";
                    $confirm["msg"] = "Metatag poprawiona pomyślnie";
                    $confirm["quick"] = true;
                } catch (\Exception $ex) {
                    $confirm["note"] = "error";
                    $confirm["msg"] = "Wystąpił błąd podczas edycji";
                }
            }
        }

        $confirm["html"] = $this->renderView(
            'AppPagesBundle:Metatag:_translateForm.html.twig',
            array(
                'form' => $form->createView(),
                'id' => $id,
                'culture' => $culture,
                'quick' => $quick,
            )
        );

        return new Response(json_encode($confirm));
    }

    /**
     *  delete Action
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
        } catch (\Exception $ex) {
            $this->setFlash('error', 'Błąd podczas usuwania '.$this->fieldName);
        }

        return new RedirectResponse($this->generateUrl($this->redirectShow));
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

            $this->setFlash('ok', 'Wybrane bloki zostały poprawnie usunięte');
        } catch (Exception $ex) {
            $this->setFlash('error', 'Błąd podczas usuwania bloku');
        }

        return new RedirectResponse($this->generateUrl($this->redirectShow, $this->mergeParams()));
    }

}
