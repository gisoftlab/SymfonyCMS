<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */
namespace App\ProductBundle\Repository;

use App\CoreBundle\Libraries\gsEntityRepository;
use App\ProductBundle\Entity\ProductOrders;

/**
 * ProductOrdersRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductOrdersRepository extends gsEntityRepository {
    
    public function getList($paginator, $page = 1, $perPage = 10, $productId = 0, $sidx = "", $sort = "asc", $search = "") {


        $query = $this->getEntityManager()
                ->createQueryBuilder('a')
                ->select("a")
                ->from($this->getTableName(), "a");
     
        
        if($productId)
            $query->andWhere($query->expr()->eq('a.'.'product', is_string($productId) ? $query->expr()->literal($productId) : $productId));
          
//        if (($search != "Szukaj") && ($search)) 
//            $query->andWhere( $query->expr()->like('LOWER(a.title)', $query->expr()->literal('%' . strtolower ($search) . '%')) );                    
//        
         if (!$sidx)
            $query->orderBy('a.id', 'ASC');
        $query->getQuery();


        return $paginator->paginate($query, $page, $perPage, array(
                    'sort_field_name' => $sidx, // sort field query parameter name
                    'sort_direction_name' => $sort     // sort direction query parameter name
                ));
    }
    
    
    public function saveOrder($order)
    {
       $query = $this->getEntityManager()
                ->createQuery('SELECT p FROM AppProductBundle:product p 
                                                    WHERE p.id =:id '
        );
       $query->setParameter("id", $order["productId"]);
       $query->getOneOrNullResult();
        
        $modul = new ProductOrders();
        $modul->setProduct($query->getOneOrNullResult());
        $modul->setName($order["name"]);
        $modul->setEmail($order["email"]);
        $modul->setDateFrom(new \DateTime($order["dateFrom"]));
        $modul->setDateTo(new \DateTime($order["dateTo"]));
        $modul->setDescription($order["description"]);                
        $modul->setCreatedAtValue();
        $this->save($modul);                
        
    }
    
    public function saveOrderCart($items)
    {
        foreach ($items as $key => $value) {                    
            $query = $this->getEntityManager()
                     ->createQuery('SELECT p FROM AppProductBundle:product p 
                                                         WHERE p.id =:id '
             );
            $query->setParameter("id", $key);
            $query->getOneOrNullResult();

             $modul = new ProductOrders();
             $modul->setProduct($query->getOneOrNullResult());
             $modul->setName($value["order"]["name"]);
             $modul->setEmail($value["order"]["email"]);
             $modul->setDateFrom(new \DateTime($value["order"]["from"]));
             $modul->setDateTo(new \DateTime($value["order"]["to"]));
             $modul->setDescription($value["order"]["description"]);                
             $modul->setCreatedAtValue();
             $this->save($modul);        
        } 
        
    }

}