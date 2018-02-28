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
* Frontend Slider controller.
*
 * @author Damian Ostraszewski <info@gisoft.pl>   
*/
class SliderController extends BaseController
{
    protected $namespace = 'WebPagesBundle';
    protected $module = 'Slider';    
    protected $fieldName = "Strony";
    protected $redirectShow = "_pages_show";        
  
    /**
    * show action
    *
    * @param Request $request    
    * @return \Symfony\Component\HttpFoundation\Response    
    */
    public function showAction(Request $request)
    {
        $lang = "pl";
        $model = $this->module;
        $template = "show";

        /**
         *  Chain cached result page redis or filename cached
         */
        $entities = $this->get('repo.page')->retrivateByParentLng(Page::PAGE_PANELS, $lang);

        $data = array(
            'entities' => $entities,
            'request' => $request,
        );

        return $this->render('WebPagesBundle:'.$model.':'.$template.'.html.twig', $data);
    }                 
}
