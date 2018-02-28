<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */
namespace App\UserBundle\Controller;

use App\UserBundle\Entity\EmailReporting;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\CoreBundle\Controller\BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Backend EmailReporting controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 */
class EmailReportingController extends BaseController
{
    protected $namespace = 'AppUserBundle';
    protected $module = 'EmailReporting';    
    protected $fieldName = "Emaile";
    protected $redirectShow = "_email_reporting_show";
  
   /**
    * display  index Action
    * 
    * @param Request $request         
    * @return \Symfony\Component\HttpFoundation\Response
    */
     public function indexAction(Request $request) {

        $page = ($request->get('page')) ? $request->get('page') : 1;
        $sidx = ($request->get('sort'))?$request->get('sort'):'';
        $sort = ($request->get('direction'))?$request->get('direction'):'desc';

        $paginator = $this->get('knp_paginator');
        $pager = $this->getRepo()->getList($paginator, $page, 50, $sidx, $sort);
       
        return $this->renderTpl('index', array(
                    'pager' => $pager,
                ));
    }
    
    /**
    * delete Action
    *     
    * @param integer $id
    * @return \Symfony\Component\HttpFoundation\RedirectResponse
    */        
    public function deleteAction($id) {
        /**
         * @var EmailReporting $model
         */
        if (!($model = $this->getRepo()->findOneBy(array('id' => $id))))
            throw new NotFoundHttpException('The '.$this->fieldName.' does not exist.');
        
        try {
            $this->getRepo()->delete($model);
            
            $this->setFlash('ok', $this->fieldName.' poprawnie usunięty');
        } catch (\Exception $ex) {
            $this->setFlash('error', 'Błąd podczas usuwania '.$this->fieldName);
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

            $this->setFlash('ok', $this->fieldName.' poprawnie usunięty');
        } catch (\Exception $ex) {
            $this->setFlash('error', 'Błąd podczas usuwania '.$this->fieldName);
        }

        return new RedirectResponse($this->generateUrl($this->redirectShow));
    }
        
}
