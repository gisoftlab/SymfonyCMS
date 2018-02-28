<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */
namespace Web\PagesBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\PagesBundle\Form\Type\Page as PageType;
use App\PagesBundle\Twig\UrlExtension as uri;
use App\CoreBundle\Controller\BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Web\PagesBundle\Form\NewsletterType;

/**
* Frontend Newsletter controller.
*
 * @author Damian Ostraszewski <info@gisoft.pl>   
*/
class NewsletterController extends BaseController
{
    protected $namespace = 'WebPagesBundle';
    protected $module = 'Newsletter';    
    protected $fieldName = "Strony";
    protected $redirectShow = "_pages_show";        
  
                       
   /**
    * show action
    *
    * @param Request $request
    * @return \Symfony\Component\HttpFoundation\RedirectResponse    
    */     
   public function showAction(Request $request) {
                       
        $template = "show";
        $form = $this->createForm(NewsletterType::class);
        if ('POST' == $request->getMethod()) {
            $form->handleReque($request);
            if ($form->isValid()) {
                $formData = $form->getData();          
                                
            }
        }
                 
        $data = array(
            'form' => $form->createView(),                  
         );
        
       return $this->render('WebPagesBundle:'.$this->module.':'.$template.'.html.twig',$data);
    }                                            
}
