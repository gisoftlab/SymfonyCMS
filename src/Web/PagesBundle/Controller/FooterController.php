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
use Web\PagesBundle\Statics\Page as Page;

/**
* Frontend Footer controller.
*
 * @author Damian Ostraszewski <info@gisoft.pl>   
*/
class FooterController extends BaseController
{
    protected $namespace = 'WebPagesBundle';
    protected $module = 'Footer';    
    protected $fieldName = "Strony";
    protected $redirectShow = "_pages_show";        
  
    /**
    * show action
    *
    * @param Request $request
    * @return \Symfony\Component\HttpFoundation\RedirectResponse    
    */
    public function showAction(Request $request) {                        
        
        $lang = $request->getLocale();
        $model = $this->module;
        $template = "show";

        /**
         *  Chain cached result page redis or filename cached
         */
        $entities = $this->get('repo.page')->retrivateByParentLng(Page::PAGE_FOOTER,$lang);

         $data = array(
            'entities' => $entities,                     
            'request' => $request,    
         );
        
        return $this->renderEsi('WebPagesBundle:'.$model.':'.$template.'.html.twig',$data);
    }                 
}
