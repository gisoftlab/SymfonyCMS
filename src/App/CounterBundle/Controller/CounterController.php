<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\CounterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\CoreBundle\Controller\BaseController;

/**
 * Backend Counter controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 */
class CounterController extends BaseController {

    protected $namespace = 'AppCounterBundle';
    protected $module = 'Counter';
    protected $fieldName = "Licznik";
    protected $redirectShow = "";

    /**
     * Display Index Counter Action
     *
     * @param Request $request     
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request) {
        $page = ($request->get('page')) ? $request->get('page') : 1;
        $sidx = ($request->get('sort')) ? $request->get('sort') : '';
        $sort = ($request->get('direction')) ? $request->get('direction') : 'asc';

        $paginator = $this->get('knp_paginator');
        $pager = $this->getRepo()->getList($paginator, $page, 50, $sidx, $sort);

        return $this->renderTpl('index', array(
                    'pager' => $pager,
        ));
    }
}
