<?php

namespace App\CoreBundle\Services;

use Geocoder\Geocoder;
use Geocoder\Provider\GoogleMaps;
use Geocoder\Provider\MapQuest;
use Geocoder\Provider\Yandex;
use Geocoder\ProviderAggregator;
use Ivory\HttpAdapter\CurlHttpAdapter;
use Geocoder\Provider\ChainProvider;
use Geocoder\Provider\GoogleMapsProvider;
use Geocoder\Provider\GoogleMapsBusinessProvider;
use Geocoder\Provider\OpenStreetMapsProvider;
use Geocoder\Provider\MapQuestProvider;
use Geocoder\Provider\YandexProvider;
use Geocoder\Provider\GeonamesProvider;
use App\CoreBundle\Services\MemcachedService;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Geolocation {
    
    private $conn;
    private $container;
    private $geocoder;
    private $mem;
    private $session;
   
    public function __construct(ContainerInterface $container, RegistryInterface $doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->conn = $this->em->getConnection();
        $this->container = $container;

        $locale = 'en_GB';
        $region = 'UK';
        
        $geocoder = new ProviderAggregator();
        $adapter  = new CurlHttpAdapter();

        $geocoder->registerProviders([
            new GoogleMaps($adapter, $locale, $region),
            new Yandex($adapter, $locale),
            new MapQuest($adapter, $locale),
//            new \Geocoder\Provider\MaxMind(
//                $adapter, '<MAXMIND_API_KEY>', $service, $useSsl
//            ),
//            new \Geocoder\Provider\ArcGISOnline(
//                $adapter, $sourceCountry, $useSsl
//            ),
        ]);

        $this->geocoder = $geocoder;
        $this->mem = $this->container->get("memcached.service");        
        $this->session = $this->container->get("session");        
    }
   
    private function getGeoPostcodeFromActData($location) {
        $sql = "select location_lat, location_lon from act_data where tradingpostcode = '$location'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();

        if ((isset($result["location_lat"])) && (isset($result["location_lon"]))) {

            return array(
                'lat' => (string) $result["location_lat"],
                'lon' => (string) $result["location_lon"],
            );
        }

        return false;
    }

    private function getGeoPostcodeFromSfProfile($location) {
        $sql = "select location_lat, location_lon from sf_guard_user_profile where postcode = '$location'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
             
        if ((isset($result["location_lat"])) && (isset($result["location_lon"]))) {

            return array(
                'lat' => (string) $result["location_lat"],
                'lon' => (string) $result["location_lon"],
            );
        }

        return false;
    }

    public function getGeocode($location, $cache = true) {
        
      // read memcached
       
      if($result = $this->mem->getElement('locationCache',$location))          
               return $result;                   
              
           
        if($cache){                           
        
            if ($result = $this->session->get('locationName/'.$location)) 
               return $result;            
            
            if ($result = $this->getGeoPostcodeFromActData($location)) {
                if (($result["lat"]) && $result["lon"]){
                    $this->session->set('locationName/'.$location, $result);                    
                    return $result;
                }
            }
            
            if ($result = $this->getGeoPostcodeFromSfProfile($location)) {
                if (($result["lat"] != "0.0000000000") && $result["lon"] != "0.0000000000"){
                    $this->session->set('locationName/'.$location, $result);                                        
                    return $result;
                }
            }
                    
        }

        try {                        
            $geocode = $this->geocoder->geocode($location);                               
        } catch (\Exception $e) {
            // echo 'Caught exception: ',  $e->getMessage(), "\n";exit;
            return false;
        }
                
        $output =  array(
            'lat' => (string) $geocode['latitude'],
            'lon' => (string) $geocode['longitude'],
        );
        
        // save memcached
        $this->mem->setElement('locationCache',$location,$output,MemcachedService::TIME_NEXT_YEAR);
                                
        return $output;
    }

}
