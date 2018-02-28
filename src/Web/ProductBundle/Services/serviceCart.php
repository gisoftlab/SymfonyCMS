<?php

namespace Web\ProductBundle\Services;

use App\ProductBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class serviceCart
 * @package Web\ProductBundle\Services
 */
class serviceCart {

    protected $_em;
    protected $_container;
    protected $cart;

    /**
     * serviceCart constructor.
     *
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
    public function clear() {
        $session = $this->_container->get('session');
        $session->set('cart', null);
    }

    /**
     * get
     * @return mixed
     */
    public function get() {
        $this->download();
        return($this->cart["item"]);
    }

    /**
     * add product
     *
     * @param array $data
     * @param  Product $product
     * @return bool|int
     */
    public function addProduct($data, $product) {

        $this->download();
        if($product){
            $this->cart['item'][$product->getId()]['product']["title"] = $product->getTitle();
            $this->cart['item'][$product->getId()]['product']["slug"] = $product->getSlug();
            $this->cart['item'][$product->getId()]['product']["price"] = $product->getPrice();
            $this->cart['item'][$product->getId()]['product']["deposit"] = $product->getDeposit();
            $this->cart['item'][$product->getId()]['order']["name"] = $data["name"];
            $this->cart['item'][$product->getId()]['order']["email"] = $data["name"];
            $this->cart['item'][$product->getId()]['order']["from"] = $data["dateFrom"];
            $this->cart['item'][$product->getId()]['order']["to"] = $data["dateTo"];
            $this->cart['item'][$product->getId()]['order']["description"] = $data["name"];
        

            $this->save();
            return true;
        }
        return false;
    }

    /**
     * delProduct
     * @param $id
     */
    public function delProduct($id) {
        $this->download();

        if (isset($this->cart['item'][$id]))
            unset($this->cart['item'][$id]);

        $this->save();
    }

    /**
     * download
     */
    private function download() {
        $session = $this->_container->get('session');
        $this->cart = $session->get('cart');
    }

    /**
     * save
     */
    private function save() {
        $session = $this->_container->get('session');
        $session->set('cart', $this->cart);
    }

}
