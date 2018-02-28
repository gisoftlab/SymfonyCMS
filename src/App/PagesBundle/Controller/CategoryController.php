<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */
namespace App\PagesBundle\Controller;

use App\PagesBundle\Entity\Category;
use App\PagesBundle\Entity\Page;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\PagesBundle\Form\CategoryType;
use App\CoreBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Backend Category controller.
 * Class CategoryController
 * @package App\PagesBundle\Controller
 *
 * @author Damian Ostraszewski <info@gisoft.pl>
 */
class CategoryController extends BaseController
{
    protected $namespace = 'AppPagesBundle';
    protected $module = 'Category';
    protected $fieldName = "Block";
    protected $redirectShow = "_pages_show";

    /**
     * display  index Action
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {

        $search = "";
        $mainPage = 0;

        $page = ($request->get('page')) ? $request->get('page') : 1;
        $sidx = ($request->get('sort')) ? $request->get('sort') : '';
        $sort = ($request->get('direction')) ? $request->get('direction') : 'asc';
        $parentId = ($request->get('parentId')) ? $request->get('parentId') : 0;

        $parent = null;
        if ($parentId) {
            /**
             * @var Category
             */
            $parent = $this->get('repo.pageBlock')->findOneBy(array("id" => $parentId));
        }

        $paginator = $this->get('knp_paginator');

        /**
         * @var ArrayCollection $pager
         */
        $pager = $this->get('repo.pageBlock')->getList($paginator, $page, 50, $parentId, $sidx, $sort, $search, $mainPage);

        return $this->render(
            'AppPagesBundle:Category:index.html.twig',
            array(
                'pager' => $pager,
                'parent' => $parent,
                'languages' => $this->get("repo.languages")->getLangLst(),
                'lang' => $this->get("repo.settings")->getLang(),
            )
        );
    }

    /**
     * display  new Action
     *
     * @param Request $request
     * @param null $parentId
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, $parentId = null)
    {

        $model = new Category();

        if ($parentId) {
            /**
             * @var Category $parent
             */
            $parent = $this->get("repo.pageBlock")->findOneBy(array("id" => $parentId));
            if ($parent) {
                $model->setParent($parent);
            }
        }

        $form = $this->createForm(CategoryType::class, $model);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                try {
                    $this->get("repo.pageBlock")->Save($model);
                    $this->setFlash('ok', 'Blok poprawiony poprawnie');
                } catch (\Exception $ex) {
                    $this->setFlash('error', 'Błąd podczas poprawiania bloku');
                }

                return new RedirectResponse(
                    $this->generateUrl('_pages_block_show', $this->mergeParams(null, array("id" => "")))
                );
            }
        }

        return $this->render(
            'AppPagesBundle:Category:new.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * display  edit Action
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {

        $model = $this->get("repo.pageBlock")->findOneBy(array('id' => $id));
        if (!$model) {
            throw new NotFoundHttpException('The Block does not exist.');
        }

        $form = $this->createForm(CategoryType::class, $model);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                try {
                    $this->get("repo.pageBlock")->Save($model);

                    $this->setFlash('ok', 'Blok poprawiony poprawnie');
                } catch (Exception $ex) {
                    $this->setFlash('error', 'Błąd podczas poprawiania bloku');
                }

                return new RedirectResponse(
                    $this->generateUrl('_pages_block_show', $this->mergeParams(null, array("PagesId" => "")))
                );
            }
        }

        return $this->render(
            'AppPagesBundle:Category:edit.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * move Action
     *
     * @param Request $request
     * @param integer $id
     * @param Page $page
     * @param integer $move
     * @return RedirectResponse
     */
    public function moveAction(Request $request, $id, $page, $move)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('AppPagesBundle:Category');
        if (!$page) {
            $page = $repo->findOneById($id);
        }

        if (!$page) {
            throw new NotFoundHttpException('The Block does not exist.');
        }

        try {
            if (($page->getParent()) && ($move == "down")) {
                $repo->moveDown($page);
            } else {
                if (($page->getParent()) && ($move == "up")) {
                    $repo->moveUp($page);
                }
            }
            $this->setFlash('ok', 'Blok przsunięty poprawnie');
        } catch (Exception $ex) {
            $this->setFlash('error', 'Błąd podczas przesuwania bloku');
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * delete Action
     *
     * @param $id
     * @param null $parentId
     * @return RedirectResponse
     */
    public function deleteAction($id, $parentId = null)
    {

        /**
         * @var Category $model
         */
        $model = $this->get("repo.pageBlock")->findOneBy(array('id' => $id));
        if (!$model) {
            throw new NotFoundHttpException('The Page does not exist.');
        }

        try {

            $this->get("repo.pageBlock")->elete($model);

            $this->setFlash('ok', 'Blok poprawnie usunięta');
        } catch (Exception $ex) {
            $this->setFlash('error', 'Błąd podczas usuwania bloku');
        }

        return new RedirectResponse(
            $this->generateUrl('_pages_block_show', $this->mergeParams(null, array("PagesId" => $parentId)))
        );
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

            $this->get("repo.pageBlock")->multiDelete($multiCheckBox);

            $this->setFlash('ok', 'Wybrane bloki zostały poprawnie usunięte');
        } catch (Exception $ex) {
            $this->setFlash('error', 'Błąd podczas usuwania bloku');
        }

        return new RedirectResponse($this->generateUrl('_pages_block_show', $this->mergeParams()));
    }

    /**
     * display  quickEdit Action
     *
     * @param Request $request
     * @param int $id
     * @param int $quick
     * @return Response
     */
    public function quickEditAction(Request $request, $id, $quick)
    {
        /**
         * @var Category $model
         */
        if (!($model = $this->get("repo.pageBlock")->findOneBy(array('id' => $id)))) {
            throw new NotFoundHttpException('The Category does not exist.');
        }

        $confirm["id"] = $id;

        $form = $this->createForm(CategoryType::class, $model);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                try {
                    $this->get("repo.pageBlock")->save($model);
                    $confirm["note"] = "ok";
                    $confirm["msg"] = "Kategoria poprawiona pomyślnie";
                    $confirm["name"] = $model->getName();
                    $confirm["quick"] = true;
                } catch (Exception $ex) {
                    $confirm["note"] = "error";
                    $confirm["msg"] = "Wystąpił błąd podczas edycji";
                }
            }
        }

        $confirm["html"] = $this->renderView(
            'AppPagesBundle:Category:_quickForm.html.twig',
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
         * @var Category $model
         */
        if (!($model = $this->get("repo.pageBlock")->retrivateCategoryByLang($id, $culture))) {
            throw new NotFoundHttpException('The Category does not exist.');
        }

        $confirm["id"] = $id;
        $parentId = ($model->getParent()) ? $model->getParent()->getId() : null;
        $feedback = ($request->query->has("feedback")) ? $request->query->get("feedback") : "";

        $form = $this->createForm(CategoryType::class, $model);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                try {
                    $model->makeSlug();
                    $model->setTranslatableLocale($culture);
                    $this->get("repo.pageBlock")->save($model);
                    $confirm["note"] = "ok";
                    $confirm["msg"] = "Kategory poprawiona pomyślnie";
                    $confirm["quick"] = true;
                } catch (\Exception $ex) {
                    $confirm["note"] = "error";
                    $confirm["msg"] = "Wystąpił błąd podczas edycji";
                }
            }
        }

        $confirm["html"] = $this->renderView(
            'AppPagesBundle:Category:_translateForm.html.twig',
            array(
                'form' => $form->createView(),
                'id' => $id,
                'feedback' => $feedback,
                'parentId' => $parentId,
                'culture' => $culture,
                'quick' => $quick,
            )
        );

        return new Response(json_encode($confirm));
    }

}
