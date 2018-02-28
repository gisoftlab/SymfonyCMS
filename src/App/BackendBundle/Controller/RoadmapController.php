<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\BackendBundle\Controller;

use App\PagesBundle\Entity\Page;
use App\CoreBundle\Controller\BaseController;

/**
 * Backend Roadmap controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 */
class RoadmapController extends BaseController {

    protected $namespace = 'AppRoadmapBundle';
    protected $module = '';
    protected $fieldName = "Roadmap";

    /**
     * Build backend roadmap
     *
     * @param null $parentId
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function prepareAction($parentId = null, $id = null) {

        $roadmap = array();

        $i = 0;
        if ($id) {
            $i++;

            /**
             *  @var Page $page
             */
            $page = $this->get('repo.page')->findOneBy(array('id' => $id));

            if ($page) {
                $roadmap[$i]["title"] = $page->getTitle();
                $roadmap[$i]["slug"] = $page->getSlug();
                $roadmap[$i]["pagesId"] = $page->getId();
                $roadmap[$i]["parentId"] = ($page->getParent()) ? $page->getParent()->getId() : '';
            }
        }


        if ($parentId) {
            $parent = $this->get('repo.page')->findOneBy(array('id' => $parentId));
            $roadmap = $this->prepareRoad($roadmap, $parent, $i);
        }

        if ($roadmap)
            $roadmap = array_reverse($roadmap, true);

        return $this->render('AppBackendBundle:Roadmap:prepare.html.twig', array(
                    'roadmap' => $roadmap,
        ));
    }

    /**
     * Build backend roadmap
     *
     * @param $roadmap
     * @param Page $parent
     * @param $i
     * @return null
     */
    private function prepareRoad($roadmap, Page $parent, $i) {
        $i++;
        if ($parent) {
            $roadmap[$i]["title"] = $parent->getTitle();
            $roadmap[$i]["slug"] = $parent->getSlug();
            $roadmap[$i]["pagesId"] = $parent->getId();
            $roadmap[$i]["parentId"] = ($parent->getParent()) ? $parent->getParent()->getId() : '';

            if (!$parent->getParent()) {
                $i++;
                $roadmap[$i]["title"] = "main";
                $roadmap[$i]["slug"] = "/";
                $roadmap[$i]["pagesId"] = "0";
                $roadmap[$i]["parentId"] = "0";
                return $roadmap;
            } else {
                $grand = $this->get('repo.page')->findOneBy(array('id' => $parent->getParent()->getId()));
                return $this->prepareRoad($roadmap, $grand, $i);
            }
        }
        return null;
    }

}
