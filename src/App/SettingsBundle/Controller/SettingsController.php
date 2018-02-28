<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\SettingsBundle\Controller;

use App\SettingsBundle\Entity\Settings;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\SettingsBundle\Form\SettingsType;
use App\CoreBundle\Controller\BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Backend Settings controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 */
class SettingsController extends BaseController {

    protected $namespace = 'AppSettingsBundle';
    protected $module = 'Settings';    
    protected $fieldName = "Ustawienia";
    protected $redirectShow = "_settings_show";
    
    /**
    * display  index Action
    * 
    * @param Request $request     
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function indexAction(Request $request) {        
        $page = ($request->get('page')) ? $request->get('page') : 1;
        $sidx = ($request->get('sort'))?$request->get('sort'):'';
        $sort = ($request->get('direction'))?$request->get('direction'):'asc';

        $paginator = $this->get('knp_paginator');
        $pager = $this->getRepo()->getList($paginator, $page, 50, $sidx, $sort);

        return $this->render(
            'AppSettingsBundle:Settings:index.html.twig',
            array(
                'pager' => $pager,
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
    public function editAction(Request $request, $id) {
        /**
         * @var Settings $model
         */
        if (!($model = $this->getRepo()->findOneBy(array('id' => $id))))
            throw new NotFoundHttpException('The ' . $this->fieldName . ' does not exist.');
        
        $form = $this->createForm(SettingsType::class, $model);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                try {
                    $this->getRepo()->save($model);

                    $this->setFlash('ok', $this->fieldName . ' poprawiony poprawnie');
                } catch (\Exception $ex) {
                    $this->setFlash('error', 'Błąd podczas poprawiania ' . $this->fieldName);
                }
                return new RedirectResponse($this->generateUrl($this->redirectShow));
            }
        }

        return $this->render(
            'AppSettingsBundle:Settings:edit.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
    
    /**
    * display  new Action
    * 
    * @param Request $request         
    * @return \Symfony\Component\HttpFoundation\Response
    */
     public function newAction(Request $request) {

        $model = new Settings();
        $form = $this->createForm(SettingsType::class, $model);
         if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                
                try {
                    $this->getRepo()->save($model);
                    
                    $this->setFlash('ok', $this->fieldName.' poprawiony poprawnie');
                } catch (\Exception $ex) {
                    $this->setFlash('error', 'Błąd podczas poprawiania '.$this->fieldName);
                }
                return new RedirectResponse($this->generateUrl($this->redirectShow));
            }
        }

        return $this->render(
             'AppSettingsBundle:Settings:new.html.twig',
             array(
                 'form' => $form->createView(),
             )
        );
    }
    
    /**
    * delete Action
    * 
    * @param int $id         
    * @return \Symfony\Component\HttpFoundation\RedirectResponse
    */
    public function deleteAction($id) {       
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

}
