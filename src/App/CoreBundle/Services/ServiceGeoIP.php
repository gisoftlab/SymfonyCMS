<?php

namespace App\CoreBundle\Services;

use GeoIp2\Database\Reader as GeoIp2DbReader;
use GeoIp2\WebService\Client as GeoIp2DbClient;

/**
 * Class ServiceGeoIP
 * @package App\CoreBundle\Services
 */
class ServiceGeoIP {

    /**
     * @var string
     */
    private $localIP = "127.0.0.1";

    /**
     * @var string
     */
    private $defaultIP = "128.127.80.167";

    /**
     * @var string
     */
    private $apiKey = "";

    /**
     * @var string
     */
    private $apiUserID = "";

    /**
     * @var
     */
    private $geoLite2city;

    /**
     * @var
     */
    private $geoLite2country;

    /**
     * @var
     */
    private $readerCity;

    /**
     * @var
     */
    private $readerCountry;

    /**
     * @var
     */
    private $clientWebService;


    /**
     * ServiceGeoIP constructor.
     *
     * @param string $apiUserID
     * @param string $apiKey
     * @param string $geoLite2city
     * @param string $geoLite2country
     */
    public function __construct($apiUserID, $apiKey, $geoLite2city, $geoLite2country) {

        $this->apiKey = $apiKey;
        $this->apiUserID = $apiUserID;
                        
        $this->geoLite2city = $geoLite2city;
        $this->geoLite2country = $geoLite2country;
        
        $this->ip_addr = $_SERVER['REMOTE_ADDR'];

        if ($this->ip_addr == $this->localIP)
            $this->ip_addr = $this->defaultIP;
    }

    /**
     * @param null $ip
     * @return \GeoIp2\Model\City
     */
    public function getIPDbCity($ip = null) {
        if(!$this->readerCity){
            $this->readerCity = $readerCity = new GeoIp2DbReader($this->geoLite2city);
            return $readerCity->city(($ip)?$ip:$this->ip_addr);                        
        }else{
            return $this->readerCity;
        }                
    }

    /**
     * @param null $ip
     * @return \GeoIp2\Model\Country
     */
    public function getIPDbCountry($ip = null) {
         if(!$this->readerCountry){
            $this->readerCountry = $reader = new GeoIp2DbReader($this->geoLite2country);
            return $reader->country(($ip)?$ip:$this->ip_addr);          
         }else{
              return $this->readerCountry;
         }        
    }

    /**
     * @param null $ip
     * @return mixed
     */
     public function getIPWebService($ip = null) {
         if(!$this->clientWebService){
            $this->clientWebService =  $clientWebService = new GeoIp2DbClient($this->apiUserID, $this->apiKey);
            return $clientWebService->city(($ip)?$ip:$this->ip_addr);
         }else{
              return $this->clientWebService;
         }        
    }

    /**
     * @param null $ip
     * @return \GeoIp2\Model\Isp
     */
    public function cityIspOrg($ip = null) {
        $reader = new GeoIp2DbReader($this->geoLite2country);
        $record = $reader->isp(($ip)?$ip:$this->ip_addr);
        return $record;
    }

    /**
     * @return null|string
     */
    public function getCountryISO() {
        return $this->getIPDbCountry()->country->isoCode;        
    }

    /**
     * @return null|string
     */
    public function getCountry() {
        return $this->getIPDbCountry()->country->name;        
    }

    /**
     * @param $culture
     * @return array|null|string
     */
    public function getCountryI18n($culture) {
        return isset($this->getIPDbCountry()->country->names[$culture])?$this->getIPDbCountry()->country->names[$culture]:"";
    }
    
    public function getMostSpecificSubdivisionIsoCode() {
        return $this->getIPDbCity()->mostSpecificSubdivision->isoCode;        
    }

    /**
     * @return null|string
     */
    public function getMostSpecificSubdivisionName() {
        return $this->getIPDbCity()->mostSpecificSubdivision->name;        
    }

    /**
     * @return null|string
     */
    public function getCityName() {
        return $this->getIPDbCity()->city->name;        
    }

    /**
     * @return float|null
     */
    public function getLatitude() {
        return $this->getIPDbCity()->location->latitude;        
    }

    /**
     * @return float|null
     */
    public function getLongitude() {
        return $this->getIPDbCity()->location->longitude;        
    }
    
      
}
