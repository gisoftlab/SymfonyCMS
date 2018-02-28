<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 */

namespace App\BackendBundle\Controller;

use App\CoreBundle\Controller\BaseController;
use App\PagesBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;

/**
 * Backend Menu controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>
 */
class MenuController extends BaseController
{

    /**
     * Build backend menu
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function buildAction(Request $request)
    {
        return $this->render(
            'AppBackendBundle:Menu:build.html.twig',
            array(
                'pages' => $this->get("repo.page")->getPagesByCategory(Category::MENU),
                'category' => Category::MENU,
                'request' => $request,
            )
        );
    }

    /**
     * Build backend menu
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function footerBuildAction(Request $request)
    {
        return $this->render(
            'AppBackendBundle:Menu:build.html.twig',
            array(
                'pages' => $this->get("repo.page")->getPagesByCategory(Category::FOOTER),
                'category' => Category::FOOTER,
                'request' => $request,
            )
        );
    }

}
