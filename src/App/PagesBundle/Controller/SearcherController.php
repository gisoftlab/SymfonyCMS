<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\PagesBundle\Controller;

use App\PagesBundle\Form\SearcherType;
use App\CoreBundle\Controller\BaseController;

/**
 * Backend Searcher controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>
 */
class SearcherController extends BaseController
{

    /**
     *  Seek Action
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function seekAction()
    {
        $stack = $this->get("request_stack");
        $request = $stack->getMasterRequest();
        $get = $request->get("searcher");

        $form = $this->createForm(SearcherType::class,$get);
        if ($form->isSubmitted()) {
            $form->handleRequest($request);
            if ($form->isValid()) {

            }
        }

        return $this->render(
            'AppPagesBundle:Searcher:seek.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
}
