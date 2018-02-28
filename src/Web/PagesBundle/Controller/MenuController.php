<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */
namespace Web\PagesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\PagesBundle\Form\PageType;
use App\PagesBundle\Twig\UrlExtension as uri;
use App\CoreBundle\Controller\BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Web\PagesBundle\Statics\Page as Page;

/**
* Frontend Menu controller.
*
 * @author Damian Ostraszewski <info@gisoft.pl>   
*/
class MenuController extends BaseController
{
    protected $namespace = 'WebPagesBundle';
    protected $module = 'Menu';    
    protected $fieldName = "Strony";
    protected $redirectShow = "_pages_show";        
  
    /**
    * show action
    *
    * @param Request $request
    * @return \Symfony\Component\HttpFoundation\Response    
    */
    public function showAction(Request $request) {                        
        
        $lang = $request->getLocale();

        $model = $this->module;
        $template = "show";
        //$slug = $request->get("slug");
        $page = null;

        $entities = $this->get('repo.page')->retrivateByBlockLang( Page::BLOCK_MENU, $lang);
         
         $data = array(
            'entities' => $entities,                     
            'request' => $request,    
            'page' => $page,    
         );
        
        return $this->renderEsi('WebPagesBundle:'.$model.':'.$template.'.html.twig',$data);
    }         
    
    /**
    * show action
    *
    * @param Request $request
    * @return \Symfony\Component\HttpFoundation\Response    
    */
    public function rentalAction(Request $request) {                        
        
        $lang = "pl";
        $model = $this->module;
        $template = "rental";
        $slug = $request->get("slug");
        $page = null;

        /**
         *  Chain cached result page redis or filename cached
         */
        $pool = $this->get("cache.chain");
        $cachedPages = $pool->getItem(sprintf('|page|%s|lang|%s|rental', Page::PAGE_RENTAL, $lang));
        if (!$cachedPages->isHit() || !$cachedPages->get()) {
            $entities = $this->get('repo.page')->retrivateByParentLng( Page::PAGE_RENTAL, $lang);
            $cachedPages->set($entities);
            $pool->save($cachedPages);
        } else {
            $entities = $cachedPages->get();
        }

        $pool = $this->get("cache.chain");
        $cachedPages = $pool->getItem(sprintf('|page|%s|lang|%s|translated', $slug, $lang));
        if (!$cachedPages->isHit() || !$cachedPages->get()) {
            $page = $this->get('repo.page')->retrivateTranslatedPage( $slug, $lang);
            $cachedPages->set($entities);
            $pool->save($cachedPages);
        } else {
            $page = $cachedPages->get();
        }


        if(!$page){
            $product = $this->get('repo.product')->retrivateBySlug($slug);
            if($product)
                $page = $product->getPage();
        }                                                                       
                                                                              
         $data = array(
            'entities' => $entities,                     
            'request' => $request,    
            'page' => $page,    
         );
        
        return $this->renderEsi('WebPagesBundle:'.$model.':'.$template.'.html.twig',$data);
    }         
}
