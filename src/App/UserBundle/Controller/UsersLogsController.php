<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\PagesBundle\Form\MetatagType;
use App\CoreBundle\Controller\BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Backend UsersLogs controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 */
class UsersLogsController extends BaseController {

    protected $namespace = 'AppUserBundle';
    protected $module = 'LoginHistory';
    protected $fieldName = "Logi użytkownika";
    protected $redirectShow = "_users_logs_show";

    /**
     * display  index Action
     * 
     * @param Request $request         
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request) {

        $page = ($request->get('page')) ? $request->get('page') : 1;
        $sidx = ($request->get('sort')) ? $request->get('sort') : '';
        $sort = ($request->get('direction')) ? $request->get('direction') : 'desc';

        $paginator = $this->get('knp_paginator');
        $pager = $this->getRepo()->getList($paginator, $page, 50, $sidx, $sort);


        return $this->renderTpl('index', array(
                    'pager' => $pager,
        ));
    }

    /**
     * delete Action
     *     
     * @param int $id        
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id) {
        if (!($model = $this->getRepo()->findOneBy(array('id' => $id))))
            throw new NotFoundHttpException('The ' . $this->fieldName . ' does not exist.');

        try {
            $this->getRepo()->delete($model);

            $this->setFlash('ok', $this->fieldName . ' poprawnie usunięty');
        } catch (\Exception $ex) {
            $this->setFlash('error', 'Błąd podczas usuwania ' . $this->fieldName);
        }
        return new RedirectResponse($this->generateUrl($this->redirectShow));
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
            $this->getRepo()->multiDelete($multiCheckBox);

            $this->setFlash('ok', $this->fieldName . ' poprawnie usunięte');
        } catch (\Exception $ex) {
            $this->setFlash('error', 'Błąd podczas usuwania ' . $this->fieldName);
        }

        return new RedirectResponse($this->generateUrl($this->redirectShow));
    }
}
