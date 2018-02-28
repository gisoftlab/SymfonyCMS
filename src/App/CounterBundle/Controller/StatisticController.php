<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */
namespace App\CounterBundle\Controller;

use App\CoreBundle\Controller\BaseController;

/**
* Backend Statistic controller.
*
 * @author Damian Ostraszewski <info@gisoft.pl>   
*/
class StatisticController extends BaseController
{
  
    protected $namespace = 'AppCounterBundle';
    protected $module = 'Counter';    
    protected $fieldName = "Statistics";
    protected $redirectShow = "";
    

    /**
     * Display  year Statistics Action
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function yearAction() {
         return $this->render('AppCounterBundle:Statistic:year.html.twig', array(
            'results' => $this->get("repo.counter")->getListCountByYear(),
        ));         
    }
    
    /**
    * Display  month Statistics Action
    *
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function monthAction() {
         return $this->render('AppCounterBundle:Statistic:month.html.twig', array(
            'results' => $this->get("repo.counter")->getListCountByMonthYear(),
        ));         
    }
    
    /**
    * Display  day Statistics Action
    *
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function dayAction() {
         return $this->render('AppCounterBundle:Statistic:day.html.twig', array(
            'results' => $this->get("repo.counter")->getListCountByDate(),
        ));         
    }
    
    /**
    * Display  ip Statistics Action
    *
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function ipAction() {
         return $this->render('AppCounterBundle:Statistic:ip.html.twig', array(
            'results' => $this->get("repo.counter")->getListCountByIPDate(),
        ));         
    }
    
    /**
    * Display  browser Statistics Action
    *
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function browserAction() {
         return $this->render('AppCounterBundle:Statistic:browser.html.twig', array(
            'results' => $this->get("repo.counter")->getListCountByBrowserDate(),
        ));         
    }               
}
