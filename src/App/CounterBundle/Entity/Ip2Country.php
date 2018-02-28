<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\CounterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\CoreBundle\Annotations as CoreAnnotation;

/**
 *
 * @ORM\Table(name="counter__ip2country", indexes={
 *      @ORM\Index(name="Ip2Country_ip_from_idx", columns={"ip_from"}),
 *      @ORM\Index(name="Ip2Country_ip_to_idx", columns={"ip_to"}),
 *      @ORM\Index(name="Ip2Country_country_code2_idx", columns={"country_code2"}),
 *      @ORM\Index(name="Ip2Country_country_code3_idx", columns={"country_code3"}),
 * })
 * 
 * @ORM\Entity(repositoryClass="App\CounterBundle\Repository\Ip2CountryRepository")  
 * @ORM\HasLifecycleCallbacks()
 */
class Ip2Country
{
             
    
 /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="ip_from", type="integer", nullable=false)
     */
    private $ipFrom;

    /**
     * @var integer
     *
     * @ORM\Column(name="ip_to", type="integer", nullable=false)
     */
    private $ipTo;

    /**
     * @var string
     *
     * @ORM\Column(name="country_code2", type="string", length=2, nullable=true)
     */
    private $countryCode2;

    /**
     * @var string
     *
     * @ORM\Column(name="country_code3", type="string", length=2, nullable=true)
     */
    private $countryCode3;

    /**
     * @var string
     *
     * @ORM\Column(name="country_name", type="string", length=50, nullable=true)
     */
    private $countryName;

     
    public function __construct()
    {                   
    }

        
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
     
    /**
     * Set ipFrom
     *
     * @param integer $ipFrom
     * @return $this
     */
    public function setIpFrom($ipFrom)
    {
        $this->ipFrom = $ipFrom;
    
        return $this;
    }

    /**
     * Get ipFrom
     *
     * @return integer 
     */
    public function getIpFrom()
    {
        return $this->ipTo;
    }
    
    /**
     * Set ipTo
     *
     * @param integer $ipTo
     * @return $this
     */
    public function setIpTo($ipTo)
    {
        $this->ipTo = $ipTo;
    
        return $this;
    }

    /**
     * Get ipTo
     *
     * @return integer 
     */
    public function getIpTo()
    {
        return $this->ipTo;
    }
    
    /**
     * Set countryCode2
     *
     * @param string $countryCode2
     * @return $this
     */
    public function setCountryCode2($countryCode2)
    {
        $this->countryCode2 = $countryCode2;
    
        return $this;
    }

    /**
     * Get countryCode2
     *
     * @return string 
     */
    public function getCountryCode2()
    {
        return $this->countryCode2;
    }
    
    /**
     * Set countryCode3
     *
     * @param string $countryCode3
     * @return $this
     */
    public function setCountryCode3($countryCode3)
    {
        $this->countryCode3 = $countryCode3;
    
        return $this;
    }

    /**
     * Get countryCode2
     *
     * @return string 
     */
    public function getCountryCode3()
    {
        return $this->countryCode3;
    }
    
    /**
     * Set countryName
     *
     * @param string $countryName
     * @return $this
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;
    
        return $this;
    }

    /**
     * Get countryName
     *
     * @return string 
     */
    public function getCountryName()
    {
        return $this->countryName;
    }
     
}