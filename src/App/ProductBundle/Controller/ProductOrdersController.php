<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */
namespace App\ProductBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use App\ProductBundle\Form\ProductItemsType;
use App\CoreBundle\Controller\BaseController;
use App\PagesBundle\Twig\UrlExtension as uri;
use Symfony\Component\HttpFoundation\Request;

/**
 * Backend ProductOrders controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 */
class ProductOrdersController extends BaseController
{
    protected $namespace = 'AppProductBundle';
    protected $module = 'ProductOrders';    
    protected $fieldName = "Zamówienie produktu";
    protected $redirectShow = "_product_orders_show";
  
    /**
    * display  index Action
    * 
    * @param Request $request     
    * @param int $page
    * @param int $pagesId         
    * @return \Symfony\Component\HttpFoundation\Response
    */
     public function indexAction(Request $request,$page = 1,$pagesId = null) {
                
        $productId = ($request->get('productId')) ? $request->get('productId') : null;
        $page = ($request->get('page')) ? $request->get('page') : 1;
        $sidx = ($request->get('sort'))?$request->get('sort'):'';
        $sort = ($request->get('direction'))?$request->get('direction'):'desc';
        
        $product = $this->get('repo.product')->findOneBy(array("id"=>$productId));                 

        $paginator = $this->get('knp_paginator');
        $pager = $this->getRepo()->getList($paginator, $page, 50, $productId,$sidx, $sort);

        
        return $this->renderTpl('index', array(
                    'pager' => $pager,
                    'product' => $product,
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
        if (!($model = $this->getRepo()->findOneBy(array('id' => $id))))
            throw new NotFoundHttpException('The ' . $this->fieldName . ' does not exist.');
        
        $productId = ($request->get('productId')) ? $request->get('productId') : null;
        $form = $this->createForm(new ProductItemsType(), $model);
        
        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                try {
                    $product = $this->get('repo.product')->findOneBy(array("id"=>$productId));        
                    $model->setProduct($product);
                    $this->getRepo()->save($model);

                    $this->setFlash('ok', $this->fieldName . ' poprawiony poprawnie');
                } catch (Exception $ex) {
                    $this->setFlash('error', 'Błąd podczas poprawiania ' . $this->fieldName);
                }
                return new RedirectResponse($this->generateUrl($this->redirectShow,  uri::mergeParams(null, array("id" => "")))); 
            }
        }

        return $this->renderTpl('edit', array(
                    'form' => $form->createView(),
                ));
    }
    
    /**
    * display  new Action
    * 
    * @param Request $request             
    * @return \Symfony\Component\HttpFoundation\Response
    */
     public function newAction(Request $request) {
        
        $model = new \App\ProductBundle\Entity\ProductItems();

        $productId = ($request->get('productId')) ? $request->get('productId') : null;
        
        $form = $this->createForm(new ProductItemsType(), $model);
        $request = $this->get('request');
        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                
                try {
                    $product = $this->get('repo.product')->findOneBy(array("id"=>$productId));        
                    $model->setProduct($product);           
                    $this->getRepo()->save($model);
                    
                    $this->setFlash('ok', $this->fieldName.' poprawiony poprawnie');
                } catch (Exception $ex) {
                    $this->setFlash('error', 'Błąd podczas poprawiania '.$this->fieldName);
                }
                return new RedirectResponse($this->generateUrl($this->redirectShow,  uri::mergeParams(null, array("id" => "")))); 
            }
        }
        
        return $this->renderTpl('new', array(        
                    'form' => $form->createView(),
                ));
    }
    
     /**
    * move Action
    * 
    * @param Request $request         
    * @param int $sequence         
    * @param int $move         
    * @return \Symfony\Component\HttpFoundation\RedirectResponse
    */
     public function moveAction(Request $request, $sequence, $move) {       

        $productId = ($request->get('productId')) ? $request->get('productId') : null;
         
        if (!$sequence)
            throw new NotFoundHttpException('The product does not exist.');

        try {
            if  ($move == "down") {
                $this->getRepo()->Lower($sequence,$productId);
            } else if ($move == "up") {
                $this->getRepo()->Higher($sequence,$productId);
            }
             $this->get('session')->getFlashBag()->set('ok', 'Produkt został przesunięty');
        } catch (Exception $ex) {
             $this->get('session')->getFlashBag()->set('error', 'Błąd podczas przesuwania produktu');
        }

        return $this->redirect($this->getRequest()->headers->get('referer'));
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
        } catch (Exception $ex) {
            $this->setFlash('error', 'Błąd podczas usuwania '.$this->fieldName);
        }
        return new RedirectResponse($this->generateUrl($this->redirectShow,  uri::mergeParams(null, array("id" => "")))); 
    }
        
}
