<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace Web\ProductBundle\Controller;

use App\ProductBundle\Entity\Product;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\PagesBundle\Form\PageType;
use App\PagesBundle\Twig\UrlExtension as uri;
use App\CoreBundle\Controller\BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Frontend Product controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 */
class ProductController extends BaseController {

    protected $namespace = 'WebProductBundle';
    protected $module = 'Product';
    protected $fieldName = "Strony";
    protected $redirectShow = "_product_show";

    public function showAction(Request $request, $ids, $perpage = 9) {

        $model = $this->module;
        $template = "show";
        $entities = null;

        $page = ($request->get('page')) ? $request->get('page') : 1;
        $sidx = ($request->get('sort')) ? $request->get('sort') : '';
        $sort = ($request->get('direction')) ? $request->get('direction') : 'asc';

        $search = $request->query->get("searcher");
        if ($search) {
            $search = $search["search"];
        }


        $paginator = $this->get('knp_paginator');

        if ($ids)
            $pager = $this->get('repo.product')->listProductByCategories($paginator, $ids, $page, $perpage, $sidx, $sort, $search);

        if (!$pager)
            throw new NotFoundHttpException('The products does not exist.');


        $data = array(
            'pager' => $pager,
        );

        return $this->render('WebProductBundle:' . $model . ':' . $template . '.html.twig', $data);
    }

    /**
     * Promoted Product
     *
     * @param Request $request
     * @return Response
     */
    public function promoteAction(Request $request) {

        $lang = $request->getLocale();
        $model = $this->module;
        $template = "promote";
        $entities = $this->get('repo.product')->retrivatePromoted(Product::PROMOTED, $lang);

        $data = array(
            'entities' => $entities,
        );

        return $this->renderEsi('WebProductBundle:' . $model . ':' . $template . '.html.twig', $data);
    }

    public function homeAction(Request $request) {

        $model = $this->module;
        $template = "home";
        $entities = null;

        $entities = $this->get('repo.product')->retrivateRandom();

        $data = array(
            'entities' => $entities,
        );

        return $this->render('WebProductBundle:' . $model . ':' . $template . '.html.twig', $data);
    }

    public function recentlyAction(Request $request) {

        $model = $this->module;
        $template = "recently";
        $entities = null;

        $entities = $this->get("product.recentlyViwed")->get();

        $data = array(
            'entities' => $entities,
        );

        return $this->renderEsi('WebProductBundle:' . $model . ':' . $template . '.html.twig', $data);
    }

}
