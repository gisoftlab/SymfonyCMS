<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */
namespace Web\PagesBundle\Controller;


use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\PagesBundle\Form\PageType;
use App\PagesBundle\Twig\UrlExtension as uri;
use App\CoreBundle\Controller\BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Web\PagesBundle\Statics\Page as Page;
use Web\PagesBundle\Form\SearcherType;

/**
* Frontend Pages controller.
*
 * @author Damian Ostraszewski <info@gisoft.pl>   
*/

class PagesController extends BaseController
{
    protected $namespace = 'WebPagesBundle';
    protected $module = 'Pages';    
    protected $fieldName = "Strony";
    protected $redirectShow = "_pages_show";
             
    /**
     * show action
     *
     * @param Request $request
     * @param string $slug
     * @param int $page
     * @return Response
     */
    public function indexAction(Request $request, $slug = "", $page = 1)
    {

        $lang = $request->getLocale();
        $model = "Pages";
        $template = "index";

        $entity = null;
        $category = null;
        $product = null;
        $ids = '';

        //$request->setLocale('de');
        //$request->getSession()->set('_locale', 'de');

        //$entity = $this->get('repo.page')->findOneBySlug($slug);
        //$page = $this->getEm()->find('App\PagesBundle\Entity\Page', 11 /*article id*/);
        //$page->setTranslatableLocale('pl');
        //$this->getEm()->refresh($page);


        if($slug) {
            $entity = $this->get('repo.page')->retrivateTranslatedPage($slug, $lang);
        }else{
            $entity = $this->get('repo.page')->retrivatePageByLang(Page::PAGE_HOME, $lang);
        }

        if (!$entity) {
            /**
             *  Chain cached result page redis or filename cached
             */
            $product = $this->get('repo.product')->retrivateBySlug($slug,$lang);

            if(!$product)
                 throw $this->createNotFoundException('Strona nieistnieje.');
             
             $product->setViwedValue();
             $this->get('repo.product')->save($product);
             $this->get("product.recentlyViwed")->addProduct($product);                       
             
             $entity = $product->getPage();
        }

        if (!$entity)
            throw new NotFoundHttpException('Strona nieistnieje.');

        /**
         *  Chain cached result metatags redis or filename cached
         */
        $meta = $this->get('repo.metatag')->getMeta($entity,$lang);
                
         /*
          *  SET CONTACT TEMPLATE
          */
         if($entity->getId() == Page::PAGE_CONTACT)
             $template = "contact";
         
         if($entity->getId() == Page::PAGE_ACCESS)
             $template = "access";
         
         if($entity->getId() == Page::PAGE_HOME)
             $template = "home";                                   
         
         if($entity->getId() == Page::PAGE_RENTAL){
             $template = "rental";     
             $category = $entity;
             /**
              *  Chain cached result page redis or filename cached
              */
             $ids = $this->get('repo.page')->retrivateIdsByPage($entity);
         }


         if($entity->getParent()){
             if($entity->getParent()->getId() == Page::PAGE_RENTAL){
                 $template = "rental";
                 $category = $entity;
               }else{
                  if($entity->getParent()->getParent())
                      if($entity->getParent()->getParent()->getId() == Page::PAGE_RENTAL){
                            $template = "rental";
                            $category = $entity->getParent();
                      }
             }

             /**
              *  Chain cached result page redis or filename cached
              */
             $ids = $this->get('repo.page')->retrivateIdsByPage($entity);
         }

        $this->get("service.counter")->CountVisit();
         
          if($product){
              $template = "rentalItem";              
          }                       
                                                                                             
         $data = array(
            'page' => $entity,
            'meta' => $meta,             
            'category' => $category, 
            'ids' => $ids,   
            'product' => $product,
            'static' => array(
                'root' => Page::PAGE_ROOT,
                'home' => Page::PAGE_HOME,
            )
             
         );
        
        return $this->render('WebPagesBundle:'.$this->module.':'.$template.'.html.twig',$data);
    }        
        
}
