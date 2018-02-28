<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */
namespace App\PagesBundle\Controller;

use App\PagesBundle\Entity\Page;
use Knp\Component\Pager\Paginator;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\PagesBundle\Form\PageType;
use App\CoreBundle\Controller\BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Backend Pages controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>
 */
class PagesController extends BaseController
{
    protected $namespace = 'AppPagesBundle';
    protected $module = 'Pages';
    protected $fieldName = "Strony";
    protected $redirectShow = "_pages_show";
    protected $culture = "pl";


    /**
     * Display index Action
     *
     * @param Request $request
     * @param int $page
     * @param int $parentId
     * @return Response
     */
    public function indexAction(Request $request, $page = 1, $parentId = null)
    {

        $search = "";
        $mainPage = 0;
        $searcher = $request->query->get("searcher");;

        if ($searcher) {
            $search = $searcher['search'];
            $mainPage = $searcher['mainpages'];
        }

        $sidx = ($request->get('sort')) ? $request->get('sort') : '';
        $sort = ($request->get('direction')) ? $request->get('direction') : 'asc';
        $category = ($request->get('category')) ? $request->get('category') : '';

        $parent = null;
        if ($parentId) {
            $parent = $this->get('repo.page')->findOneBy(array(
                "id" => $parentId,
            ));
        }

        /**
         * @var Paginator $paginator
         */
        $paginator = $this->get('knp_paginator');
        $pager = $this->get('repo.page')->getList($paginator, $page, 50, $parentId, $sidx, $sort, $search, $mainPage,$category);

        return $this->render(
            'AppPagesBundle:Pages:index.html.twig',
            array(
                'pager' => $pager,
                'parent' => $parent,
                'languages' => $this->get("repo.languages")->getLangLst(),
                'lang' => $this->get("repo.settings")->getLang(),
            )
        );
    }

    /**
     * Display new Action
     *
     * @param Request $request
     * @param int $parentId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, $parentId = null)
    {
        $model = new Page();

        if ($parentId) {
            $parent = $this->get("repo.page")->findOneBy(array("id" => $parentId));
            if ($parent) {
                $model->setParent($parent);
            }
        }

        $form = $this->createForm(PageType::class, $model);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                try {
                    $model->makeSlug();

                    $this->get("repo.page")->verify();
                    $this->get("repo.page")->save($model);
                    $this->setFlash('ok', 'Strona poprawiona poprawnie');
                } catch (\Exception $ex) {
                    $this->setFlash('error', 'Błąd podczas poprawiania strony');
                }

                return new RedirectResponse(
                    $this->generateUrl('_pages_show', $this->mergeParams(null, array("id" => "")))
                );
            }
        }

        return $this->render(
            'AppPagesBundle:Pages:new.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * display  edit Action
     *
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        /**
         * @var Page $model
         */
        if (!($model = $this->get("repo.page")->findOneBy(array('id' => $id)))) {
            throw new NotFoundHttpException('The pages does not exist.');
        }

        $form = $this->createForm(PageType::class, $model);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                try {

                    $model->makeSlug();
                    //$this->get("repo.page")->verify();
                    $this->get("repo.page")->save($model);
                    $this->setFlash('ok', 'Strona poprawiona poprawnie');
                } catch (Exception $ex) {
                    $this->setFlash('error', 'Błąd podczas poprawiania strony');
                }

                return new RedirectResponse(
                    $this->generateUrl('_pages_show', $this->mergeParams(null, array("PagesId" => "")))
                );
            }
        }

        return $this->render(
            'AppPagesBundle:Pages:edit.html.twig',
            array(
                'form' => $form->createView(),
                'pageId' => $id,
            )
        );
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
         * @var Page $model
         */
        if (!($model = $this->get("repo.page")->findOneBy(array('id' => $id)))) {
            throw new NotFoundHttpException('The pages does not exist.');
        }

        try {
            $model->setPublished($value);
            $this->get("repo.page")->save($model);

            $this->setFlash('ok', 'Strona opublikowana');
        } catch (Exception $ex) {
            $this->setFlash('error', 'Błąd podczas publikowania strony');
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     *  move Action
     *
     * @param Request $request
     * @param $id
     * @param $move
     * @return RedirectResponse
     */
    public function moveAction(Request $request, $id, $move)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('AppPagesBundle:Page');

        /**
         * @var Page $page
         */
        $page = $repo->findOneById($id);
        if (!$page) {
            throw new NotFoundHttpException('The pages does not exist.');
        }

        try {
            if (($page->getParent()) && ($move == "down")) {
                $repo->moveDown($page);
            } else {
                if (($page->getParent()) && ($move == "up")) {
                    $repo->moveUp($page);
                }
            }
            $this->setFlash('ok', 'Strona przsunięta poprawnie');
        } catch (Exception $ex) {
            $this->setFlash('error', 'Błąd podczas przesuwania strony');
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     *  quickEdit Action
     *
     * @param Request $request
     * @param int $id
     * @param int $quick
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function quickEditAction(Request $request, $id, $quick)
    {
        /**
         * @var Page $page
         */
        if (!($page = $this->get("repo.page")->findOneBy(array('id' => $id)))) {
            throw new NotFoundHttpException('The pages does not exist.');
        }

        $parentId = ($page->getParent()) ? $page->getParent()->getId() : null;
        $feedback = ($request->query->has("feedback")) ? $request->query->get("feedback") : "";
        $confirm["id"] = $id;

        $form = $this->createForm(PageType::class, $page);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                try {

                    $page->makeSlug();
                    $this->get("repo.page")->verify();
                    $this->get("repo.page")->save($page);

                    $confirm["note"] = "ok";
                    $confirm["msg"] = "Strona poprawiona pomyślnie";
                    $confirm["title"] = $page->getTitle();
                    $confirm["slug"] = $page->getSlug();
                    $confirm["published"] = $page->getPublished();
                    $confirm["quick"] = true;
                } catch (Exception $ex) {
                    $confirm["note"] = "error";
                    $confirm["msg"] = "Wystąpił błąd podczas edycji";
                }
            }
        }

        $confirm["html"] = $this->renderView(
            'AppPagesBundle:Pages:_quickForm.html.twig',
            array(
                'form' => $form->createView(),
                'pageId' => $id,
                'feedback' => $feedback,
                'parentId' => $parentId,
                'quick' => $quick,
            )
        );


        return new Response(json_encode($confirm));
    }

    /**
     *  translate Action
     *
     * @param Request $request
     * @param int $id
     * @param int $quick
     * @param int $culture
     * @return Response
     */
    public function translateAction(Request $request, $id, $quick, $culture)
    {
        /**
         * @var Page $page
         */
        if (!($page = $this->get("repo.page")->retrivatePageByLang($id, $culture))) {
            throw new NotFoundHttpException('The pages does not exist.');
        }

        $confirm["id"] = $id;
        $parentId = ($page->getParent()) ? $page->getParent()->getId() : null;
        $feedback = ($request->query->has("feedback")) ? $request->query->get("feedback") : "";
        $category = $page->getCategory();

        //$page->setTranslatableLocale($culture);
        $form = $this->createForm(PageType::class, $page);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                try {
                    $page->setCategory($category);
                    $page->makeSlug();
                    $page->setTranslatableLocale($culture);
                    $this->get("repo.page")->save($page);
                    $confirm["note"] = "ok";
                    $confirm["msg"] = "Strona poprawiona pomyślnie";
                    $confirm["quick"] = true;
                } catch (Exception $ex) {
                    $confirm["note"] = "error";
                    $confirm["msg"] = "Wystąpił błąd podczas edycji";
                }
            }
        }

        $confirm["html"] = $this->renderView(
            'AppPagesBundle:Pages:_translateForm.html.twig',
            array(
                'form' => $form->createView(),
                'pageId' => $id,
                'feedback' => $feedback,
                'parentId' => $parentId,
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
     * @param null $parentId
     * @return RedirectResponse
     */
    public function deleteAction($id, $parentId = null)
    {

        if (!($model = $this->get("repo.page")->findOneBy(array('id' => $id)))) {
            throw new NotFoundHttpException('The Page does not exist.');
        }

        try {
            $this->get("repo.page")->delete($model);
            $this->setFlash('ok', 'Strona poprawnie usunięta');
        } catch (Exception $ex) {
            $this->setFlash('error', 'Błąd podczas usuwania strony');
        }

        return new RedirectResponse(
            $this->generateUrl('_pages_show', $this->mergeParams(null, array("PagesId" => $parentId)))
        );
    }

    /**
     *  multi Action
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function multiAction(Request $request)
    {
        $multiCheckBox = $request->request->get("multiCheckBox");

        try {
            $this->get("repo.page")->multiDelete($multiCheckBox);

            $this->setFlash('ok', 'Wybrane strony zostały poprawnie usunięte');
        } catch (\Exception $ex) {
            $this->setFlash('error', 'Błąd podczas usuwania stron');
        }

        return new RedirectResponse($this->generateUrl('_pages_show', $this->mergeParams()));
    }

}
