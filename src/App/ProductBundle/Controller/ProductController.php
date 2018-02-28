<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\ProductBundle\Controller;

use App\ProductBundle\Entity\Product;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\ProductBundle\Form\ProductType;
use App\CoreBundle\Controller\BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\PagesBundle\Twig\UrlExtension as uri;
use App\FilesBundle\Entity\File;

/**
 * Backend Product controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 */
class ProductController extends BaseController {

    protected $namespace = 'AppProductBundle';
    protected $module = 'Product';
    protected $fieldName = "Produkty";
    protected $redirectShow = "_product_show";
    protected $culture = "pl";

    /**
    * display  index Action
    * 
    * @param Request $request     
    * @param integer $page
    * @param integer $pagesId
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function indexAction(Request $request, $page = 1, $pagesId = null) {

        $search = "";
        $page = ($request->get('page')) ? $request->get('page') : 1;
        $sidx = ($request->get('sort')) ? $request->get('sort') : '';
        $sort = ($request->get('direction')) ? $request->get('direction') : 'asc';
        $pagesId = ($request->get('PagesId')) ? $request->get('PagesId') : 0;

        $parent = null;
        if ($pagesId)
            $parent = $this->get('repo.page')->findOneBy(array("id" => $pagesId));

        $paginator = $this->get('knp_paginator');
        $pager = $this->get('repo.product')->getList($paginator, $page, 50, $pagesId, $sidx, $sort, $search);

        return $this->render(
            'AppProductBundle:Product:index.html.twig',
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
    * @param int $pagesId    
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function newAction(Request $request, $pagesId = null) {

        $model = new Product();

        if ($pagesId) {
            /**
             * @var Page $parent
             */
            $parent = $this->get("repo.page")->findOneBy(array("id" => $pagesId));
            if ($parent)
                $model->setPage($parent);
        }

        //$fileTemp = $model->getIconPromoted();
        $model->setIconPromoted(null);
        $form = $this->createForm(ProductType::class, $model);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                try {
                    $this->get("repo.product")->save($model);

                    $this->get('session')->getFlashBag()->set('ok', 'Produkt poprawiona poprawnie');
                } catch (\Exception $ex) {
                    $this->get('session')->getFlashBag()->set('error', 'Błąd podczas poprawiania produktu');
                }

                return new RedirectResponse($this->generateUrl('_product_show', uri::mergeParams(null, array("id" => ""))));
            }
        }

        return $this->render($this->namespace . ':' . $this->module . ':new.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

     /**
    * display  edit Action
    * 
    * @param Request $request         
    * @param int $id    
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function editAction(Request $request, $id) {

        /**
         * @var Product $model
         */
        if (!($model = $this->get("repo.product")->findOneBy(array('id' => $id))))
            throw new NotFoundHttpException('The pages does not exist.');

        $fileTemp = $model->getIconPromoted();
        $model->setIconPromoted(null);
        $form = $this->createForm(ProductType::class, $model);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                try {
                    $data = $form->getData();
                    $fileData = $data->getIconPromoted();
                    $data->setIconPromoted(null);

                    if (isset($fileData["sourceFile"])) {
                        $file = null;
                        $file = $this->get("service.uploader")->Upload($fileData["sourceFile"], $fileData["sourceTitle"], $id, File::$FileContextProduct);
                        $model->setIconPromoted($file);
                    } else {
                        $model->setIconPromoted($fileTemp);
                    }

                    $this->get("repo.product")->save($model);
                    $this->get('session')->getFlashBag()->set('ok', 'Strona poprawiona poprawnie');
                } catch (\Exception $ex) {
                    $this->get('session')->getFlashBag()->set('error', 'Błąd podczas poprawiania strony');
                }
                return new RedirectResponse($this->generateUrl('_product_show', uri::mergeParams(null, array("id" => ""))));
            }
        }

        return $this->render($this->namespace . ':' . $this->module . ':edit.html.twig', array(
                    'form' => $form->createView(),
                    'id' => $id,
        ));
    }

    /**
     * published Action
     *
     * @param Request $request
     * @param $id
     * @param $value
     * @return RedirectResponse
     */
    public function publishedAction(Request $request, $id, $value) {

        /**
         * @var Product $model
         */
        if (!($model = $this->get("repo.product")->findOneBy(array('id' => $id))))
            throw new NotFoundHttpException('The pages does not exist.');

        try {
            $model->setPublished($value);
            $this->get("repo.product")->save($model);

            $this->get('session')->getFlashBag()->set('ok', 'Strona opublikowana');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', 'Błąd podczas publikowania strony');
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * eleteIconPromoted Action
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteIconPromotedAction(Request $request, $id) {

        /**
         * @var Product $model
         */
        if (!($model = $this->get("repo.product")->findOneBy(array('id' => $id))))
            throw new NotFoundHttpException('The pages does not exist.');

        try {
            $filesId = $model->getIconPromoted();
            $model->setIconPromoted(null);
            $this->get("repo.product")->save($model);

            $this->get("repo.files")->DeleteFile($filesId);

            $this->get('session')->getFlashBag()->set('ok', 'Produkt został promowany');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', 'Błąd podczas dodania do promowanych');
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * promoted Action
     *
     * @param Request $request
     * @param $id
     * @param $value
     * @return RedirectResponse
     */
    public function promotedAction(Request $request, $id, $value) {

        /**
         * @var Product $model
         */
        if (!($model = $this->get("repo.product")->findOneBy(array('id' => $id))))
            throw new NotFoundHttpException('The pages does not exist.');

        try {
            $model->setPromoted($value);
            $this->get("repo.product")->save($model);

            $this->get('session')->getFlashBag()->set('ok', 'Produkt został promowany');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', 'Błąd podczas dodania do promowanych');
        }

        return $this->redirect($request->headers->get('referer'));
    }

      /**
    * move Action
    * 
    * @param Request $request         
    * @param integer $sequence
    * @param integer $move
    * @param integer|null $category
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function moveAction(Request $request, $sequence, $move, $category = null) {

        if (!$sequence)
            throw new NotFoundHttpException('The product does not exist.');

        try {
            if ($move == "down") {
                $this->get("repo.product")->Lower($sequence, $category);
            } else if ($move == "up") {
                $this->get("repo.product")->Higher($sequence, $category);
            }
            $this->get('session')->getFlashBag()->set('ok', 'Produkt został przesunięty');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', 'Błąd podczas przesuwania produktu');
        }

        return $this->redirect($request->headers->get('referer'));
    }

     /**
    * quickEdit Action
    * 
    * @param Request $request         
    * @param integer $id
    * @param integer $quick
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function quickEditAction(Request $request, $id, $quick) {

        /**
         * @var Product $model
         */
        if (!($model = $this->get("repo.product")->findOneBy(array('id' => $id))))
            throw new NotFoundHttpException('The pages does not exist.');

        $pageId = ($model->getPage()) ? $model->getPage()->getId() : null;
        $feedback = ($request->query->has("feedback") ) ? $request->query->get("feedback") : "";
        $confirm["id"] = $id;

        $form = $this->createForm(ProductType::class, $model);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                try {

                    $this->get("repo.product")->save($model);

                    $confirm["note"] = "ok";
                    $confirm["msg"] = "Produkt poprawiony pomyślnie";
                    $confirm["title"] = $model->getTitle();
                    $confirm["slug"] = $model->getSlug();
                    $confirm["published"] = $model->getPublished();
                    $confirm["promoted"] = $model->getPromoted();
                    $confirm["quick"] = true;
                } catch (\Exception $ex) {
                    $confirm["note"] = "error";
                    $confirm["msg"] = "Wystąpił błąd podczas edycji";
                }
            }
        }

        $confirm["html"] = $this->renderView('AppProductBundle:Product:_quickForm.html.twig', array(
            'form' => $form->createView(),
            'id' => $id,
            'feedback' => $feedback,
            'PageId' => $pageId,
            'quick' => $quick,
        ));

        return new Response(json_encode($confirm));
    }

     /**
    * delete Action
    *
    * @param integer $id
    * @param integer $pagesId
    * @return \Symfony\Component\HttpFoundation\RedirectResponse
    */
    public function deleteAction($id, $pagesId = null) {

        /**
         * @var Product $model
         */
        if (!($model = $this->get("repo.product")->findOneBy(array('id' => $id))))
            throw new NotFoundHttpException('The Product does not exist.');

        try {
            $icon = $model->getIcon();
            $iconPromoted = $model->getIconPromoted();

            foreach ($model->getGallery() as $key => $value) {
                $this->getRepo()->delete($value);
            }

            $this->getRepo()->delete($model);
            if ($icon) {
                $this->get("repo.files")->DeleteFile($icon);
            }
            if ($iconPromoted) {
                $this->get("repo.files")->DeleteFile($iconPromoted);
            }

            $this->get('session')->getFlashBag()->set('ok', 'Produkt poprawnie usunięty');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', 'Błąd podczas usuwania produktu');
        }
        return new RedirectResponse($this->generateUrl('_product_show', uri::mergeParams(null, array("PagesId" => $pagesId))));
    }

    /**
     * multi Action
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function multiAction(Request $request) {

        $multiCheckBox = $request->request->get("multiCheckBox");
        try {

            $this->get("repo.product")->multiDelete($multiCheckBox);

            $this->get('session')->getFlashBag()->set('ok', 'Wybrane strony zostały poprawnie usunięte');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', 'Błąd podczas usuwania stron');
        }
        return new RedirectResponse($this->generateUrl('_product_show', uri::mergeParams()));
    }

}
