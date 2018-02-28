<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */
namespace App\CounterBundle\Repository;

use Doctrine\ORM\EntityRepository;
use App\CoreBundle\Libraries\gsEntityRepository;
use App\CounterBundle\Entity\Country;

/**
 * CountryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CountryRepository extends gsEntityRepository {

    /**
     * @param null $ip
     * @return null|Country
     */
     public function getCoutryByIp($ip = null) {
        
       if (is_null($ip))
            $ip = $_SERVER['REMOTE_ADDR'];
        $ip = long2ip(ip2long($ip)); //nettoie l'adresse
        
        $query = $this->getEntityManager()
                ->createQuery('SELECT p FROM AppCounterBundle:Ip2Country p                                        
                                            WHERE p.ipFrom <= :ipFrom
                                                        and p.ipTo >= :ipTo
                                                    ');
        $query->setParameter("ipFrom", Country::inet_aton($ip));
        $query->setParameter("ipTo", Country::inet_aton($ip));
         
        $ip2country = $query->getOneOrNullResult();
        
          if (!$ip2country) {
            return false;
        } else {
            $country = $this->findOneBy(array("iso"=>$ip2country->getCountryCode2()));          
            if (!$country) {
                return false;
            }
        }
        return $country;
                
    }
    
}
