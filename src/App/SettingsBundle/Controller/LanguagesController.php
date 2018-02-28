<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\SettingsBundle\Controller;

use App\SettingsBundle\Entity\Languages;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\SettingsBundle\Form\LanguagesType;
use App\CoreBundle\Controller\BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Backend Languages controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 */
class LanguagesController extends BaseController {
    
    protected $namespace = 'AppSettingsBundle';
    protected $module = 'Languages';    
    protected $fieldName = "Tłumaczenia";
    protected $redirectShow = "_languages_show";
    
    /**
    * display  index Action
    * 
    * @param Request $request     
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function indexAction(Request $request) {        

        $page = ($request->get('page')) ? $request->get('page') : 1;
        $sidx = ($request->get('sort')) ? $request->get('sort') : 'a.sequence';
        $sort = ($request->get('direction')) ? $request->get('direction') : 'asc';                                
   
        $paginator = $this->get('knp_paginator');
        $pager = $this->getRepo()->getList($paginator, $page, 50, $sidx, $sort);

        return $this->render(
            'AppSettingsBundle:Languages:index.html.twig',
            array(
                'pager' => $pager,
                'sidx' => $sidx,
            )
        );
    }

    /**
    * display  edit Action
    * 
    * @param Request $request     
    * @param integer $id
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function editAction(Request $request, $id ) {

        /**
         * @var Languages $model
         */
        if (!($model = $this->getRepo()->findOneBy(array('id' => $id))))
            throw new NotFoundHttpException('The ' . $this->fieldName . ' does not exist.');

        $model->setCulture($model->getLanguage());
        $form = $this->createForm(LanguagesType::class, $model);

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
            'AppSettingsBundle:Languages:edit.html.twig',
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

        $model = new Languages();
        $form = $this->createForm(LanguagesType::class, $model);
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
            'AppSettingsBundle:Languages:new.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
    * delete Action
    * 
    * @param integer $id
    * @return \Symfony\Component\HttpFoundation\RedirectResponse
    */
    public function deleteAction($id) {
        /**
         * @var Languages $model
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
     * activation Language Function
     *
     * @param Request $request
     * @param integer $value
     * @return RedirectResponse
     */
    private function activationLanguage(Request $request, $value)
    {
        /**
         * @var Languages $model
         */
         $model = $this->getRepo()->findOneBy(array('id'=> $request->get("id")));
         if (!$model) 
            throw new NotFoundHttpException('The '.$this->fieldName.' does not exist.');        
    
        try{
            $model->setIsUsed($value);
            $this->getRepo()->save($model);      
            
             $this->get('session')->getFlashBag()->set('ok', 'Status '.$this->fieldName.' został zmieniony!');
         }  catch ( \Exception $ex ){
              $this->get('session')->getFlashBag()->set('error', 'Bład podczas zmiany statusu '.$this->fieldName);             
         }
                  
         return $this->redirect($request->headers->get('referer'));
    }

    /**
     * activate Action
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function activateAction(Request $request) {
       return $this->activationLanguage($request, true);
    }

    /**
     * inactivate Action
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function inactivateAction(Request $request) {
       return  $this->activationLanguage($request, false);
    }

    /**
     * higher Action
     *
     * @param Request $request
     * @param integer $id
     * @param string $sequence
     * @param string $sidx
     * @return RedirectResponse
     */
    public function higherAction(Request $request, $id, $sequence, $sidx)
    {
        /**
         * @var Languages $model
         */
       if (!($model = $this->getRepo()->findOneBy(array('id'=> $id)))) 
            throw new NotFoundHttpException('The '.$this->fieldName.' does not exist.');        
                         
                if ($sidx != 'a.sequence')
                 $this->get('session')->getFlashBag()->set('notice', 'Aby ustawić kolejność najpierw musisz posortować po kolumnie "Przesuń" ');
            else {
                if ($this->getRepo()->higher($sequence))
                      $this->get('session')->getFlashBag()->set('ok', 'Język przunięta do góry');
                else
                     $this->get('session')->getFlashBag()->set('error', 'Wystąpił błąd podczas przunięcia do góry');
            }                              
                  
         return $this->redirect($request->headers->get('referer'));
    }

    /**
     * lower Action
     *
     * @param Request $request
     * @param integer $id
     * @param string $sequence
     * @param string $sidx
     * @return RedirectResponse
     */
    public function lowerAction(Request $request, $id, $sequence, $sidx)
   {                           
         if (!($model = $this->getRepo()->findOneBy(array('id'=> $id)))) 
            throw new NotFoundHttpException('The '.$this->fieldName.' does not exist.');        
            
             
                if ($sidx != 'a.sequence')
                 $this->get('session')->getFlashBag()->set('notice', 'Aby ustawić kolejność najpierw musisz posortować po kolumnie "Przesuń" ');
            else {
                if ($this->getRepo()->lower($sequence))
                      $this->get('session')->getFlashBag()->set('ok', 'Język przunięta na dół');
                else
                     $this->get('session')->getFlashBag()->set('error', 'Wystąpił błąd podczas przunięcia na dół');
            }                              
                  
         return $this->redirect($request->headers->get('referer'));
    }   

}
