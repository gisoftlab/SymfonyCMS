<?php

namespace Web\ProductBundle\Services;

use App\ProductBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class serviceRecently
 * @package Web\ProductBundle\Services
 */
class serviceRecently{

    protected $_em;
    protected $_container;
    protected $recentlyViwed = array();
    protected $max = 4;

    /**
     * serviceRecently constructor.
     * @param EntityManager $em
     * @param ContainerInterface $container
     */
    public function __construct(EntityManager $em, ContainerInterface $container) {

        $this->_em = $em;
        $this->_container = $container;                      
        $this->download();
    }

    /**
     * clear
     */
    public function clear()
    {
        $session=$this->_container->get('session');
        $session->set('recentlyViwed', null);
    }

    /**
     * get
     * @return array
     */
    public function get()
    {
        $this->download();
        return($this->recentlyViwed);
    }

    /**
     * add product
     *
     * @param Product $product
     * @return bool
     */
    public function addProduct(Product $product)
    {
        $this->download();        
        if(count($this->recentlyViwed['item']) < $this->max){
            $this->recentlyViwed['item'][$product->getId()]['title'] = $product->getTitle();
            $this->recentlyViwed['item'][$product->getId()]['slug'] = $product->getSlug();
            $this->save();
        }else{
            $items = $this->recentlyViwed['item'];
            $this->clear();
            $this->get();
            $this->recentlyViwed['item'][$product->getId()]['title'] = $product->getTitle();
            $this->recentlyViwed['item'][$product->getId()]['slug'] = $product->getSlug();
            $i=0;
            foreach ($items as $key => $value) {
                $i++;
                if($i < $this->max)
                    $this->recentlyViwed['item'][$key] = $items[$key];
            }            
             $this->save();
        }
        return true;
    }

    /**
     * download
     */
    private function download()
    {
        $session=$this->_container->get('session');
        $this->recentlyViwed = $session->get('recentlyViwed');
    }

    /**
     * save
     */
    private function save()
    {
        $session=$this->_container->get('session');
        $session->set('recentlyViwed', $this->recentlyViwed);
    }
    

}
