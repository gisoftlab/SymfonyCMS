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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\UserBundle\Form\UserType;
use App\UserBundle\Entity\User;
use App\CoreBundle\Controller\BaseController;

/**
 * Backend Users controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 */
class UsersController extends BaseController {

    protected $namespace = 'AppUserBundle';
    protected $module = 'Users';
    protected $fieldName = "Użytkownik";
    protected $redirectShow = "_users_show";

    /**
     * display  index Action
     * 
     * @param Request $request         
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request) {

        $page = ($request->get('page')) ? $request->get('page') : 1;
        $sidx = ($request->get('sidx')) ? $request->get('sidx') : 'Id';
        $sort = ($request->get('sort')) ? $request->get('sort') : 'ASC';

        $paginator = $this->get('knp_paginator');
        $pager = $this->get('repo.user')->getList($paginator, $page, 50, $sidx, $sort);

        return $this->render('AppUserBundle:Users:index.html.twig', array(
                    'pager' => $pager,
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

        $model = $this->get("repo.user")->findOneBy(array('id' => $id));
        if (!$model)
            throw new NotFoundHttpException('The User does not exist.');

        $form = $this->createForm(UserType::class, $model);

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $userManager = $this->get('fos_user.user_manager');
                try {
                    $userManager->updateUser($model);
                    $this->get('session')->getFlashBag()->set('ok', 'Użytkownik poprawiony poprawnie');
                } catch (\Exception $ex) {
                    $this->get('session')->getFlashBag()->set('error', 'Błąd podczas poprawiania użytkownika');
                }
                return new RedirectResponse($this->generateUrl('_users_show'));
            }
        }

        return $this->render('AppUserBundle:Users:edit.html.twig', array(
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

        $model = new User();
        $form = $this->createForm(UserType::class, $model);

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $userManager = $this->get('fos_user.user_manager');
                try {
                    $userManager->updateUser($model);
                    $this->get('session')->getFlashBag()->set('ok', 'Użytkownik poprawiony poprawnie');
                } catch (\Exception $ex) {
                    $this->get('session')->getFlashBag()->set('error', 'Błąd podczas poprawiania użytkownika');
                }
                return new RedirectResponse($this->generateUrl('_users_show'));
            }
        }

        return $this->render('AppUserBundle:Users:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * delete Action
     * 
     * @param int $id             
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id) {

        /**
         * @var User $model
         */
        $model = $this->get("repo.user")->findOneBy(array('id' => $id));
        if (!$model)
            throw new NotFoundHttpException('The User does not exist.');

        $userManager = $this->get('fos_user.user_manager');
        try {
            $userManager->deleteUser($model);
            $this->get('session')->getFlashBag()->set('ok', 'Użytkownik ' . $model->getUsername() . ' usunięty');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', 'Błąd podczas usuwania użytkownika');
        }
        return new RedirectResponse($this->generateUrl('_users_show'));
    }

    /**
     * activation User Function
     *
     * @param Request $request
     * @param integer $value
     * @return RedirectResponse
     */
    private function activationUser(Request $request, $value) {

        /**
         * @var User $model
         */
        $model = $this->get("repo.user")->findOneBy(array('id' => $request->get("id")));
        if (!$model)
            throw new NotFoundHttpException('The User does not exist.');

        try {
            $em = $this->get('doctrine')->getEntityManager();
            $model->setEnabled($value);
            $em->persist($model);
            $em->flush();
            $this->get('session')->getFlashBag()->set('ok', 'Status użytkownika został zmieniony!');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', 'Bład podczas zmiany statusu użytkownika');
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
        return $this->activationUser($request, true);
    }

    /**
     * inactivate Action
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function inactivateAction(Request $request) {
        return $this->activationUser($request, false);
    }

    /**
     * block User Function
     *
     * @param Request $request
     * @param integer $value
     * @return RedirectResponse
     */
    private function blockedUser(Request $request, $value) {

        /**
         * @var User $model
         */
        $model = $this->get("repo.user")->findOneBy(array('id' => $request->get("id")));
        if (!$model)
            throw new NotFoundHttpException('The User does not exist.');

        try {
            $em = $this->get('doctrine')->getEntityManager();
            $model->setLocked($value);
            $em->persist($model);
            $em->flush();
            $this->get('session')->getFlashBag()->set('ok', 'Status użytkownika został zmieniony!');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', 'Bład podczas zmiany statusu użytkownika');
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * block Action
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function blockAction(Request $request) {
        return $this->blockedUser($request, true);
    }

    /**
     * unblock Action
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function unblockAction(Request $request) {
        return $this->blockedUser($request, false);
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
            $this->get("repo.user")->multiDelete($multiCheckBox);

            $this->get('session')->getFlashBag()->set('ok', 'Wybranii uzytkownicy zostali poprawnie usunięci');
        } catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->set('error', 'Błąd podczas usuwania uzytkownikow');
        }

        return new RedirectResponse($this->generateUrl('_users_show', $this->mergeParams()));
    }

}
