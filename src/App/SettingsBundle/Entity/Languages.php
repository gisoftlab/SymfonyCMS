<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\SettingsBundle\Entity;

use App\CounterBundle\Entity\Country;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\CoreBundle\Annotations as CoreAnnotation;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Languages entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 *
 * @ORM\Table(name="settings__languages")
 * @ORM\Entity(repositoryClass="App\SettingsBundle\Repository\LanguagesRepository")  
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("country") 
 */
class Languages
{
             
    const IS_USED_YES = 1;
    const IS_USED_NO = 0;
    
/**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;          
                        
     /**
     * @var country
     *
     * @ORM\OneToOne(targetEntity="App\CounterBundle\Entity\Country", cascade={"persist"})
     * @ORM\JoinColumn(name="country", referencedColumnName="iso", nullable=true)
     */
    
    private $country;
    
    /**
     * @var string $culture
     * 
     * @ORM\Column(name="culture", type="string", length=6, nullable=true)
     */      
    private $culture;    
    
    /**
     * @var string $countryName
     * 
     * @ORM\Column(name="country_name", type="string", length=255, nullable=true)
     */      
    private $countryName;    
    
    
    /**
     * @var int $code;     
     * @ORM\Column(name="code", type="integer", nullable=false)
     */
     private $code = 0;    
     
     /**
     * @var int $sequence;
     * @CoreAnnotation\SetMaxSequance()
     * @ORM\Column(name="sequence", type="integer", nullable=false)
     */
     private $sequence = 0;         
     
     /**
     * @var boolean $isUsed
     *
     * @ORM\Column(name="is_used", type="boolean", nullable=true)
     */
    private $isUsed = false;  
        
    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;
    
    /**
     * @var \DateTime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

            
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
     * Set country
     *
     * @param string $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }
    
     /**
     * Set culture
     *
     * @param string $culture
     * @return $this
     */
    public function setCulture($culture)
    {
        $this->culture = $culture;
    
        return $this;
    }
    
    /**
     * Get culture
     *
     * @return string 
     */
    public function getCulture()
    {
        return $this->culture;
    }
    
    public function getLanguage(){
        $culture = explode("_", $this->culture);
        return  isset($culture[0])?$culture[0]:"";
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
    
     /**
     * Set code
     *
     * @param integer $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return integer 
     */
    public function getCode()
    {
        return $this->code;
    }
    
     /**
     * Set sequence
     *
     * @param integer $sequence
     * @return $this
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    
        return $this;
    }

    /**
     * Get sequence
     *
     * @return integer 
     */
    public function getSequence()
    {
        return $this->sequence;
    }
    
     /**
     * Set isUsed
     *
     * @param boolean $isUsed
     * @return $this
     */
    public function setIsUsed($isUsed)
    {
        $this->isUsed = $isUsed;
    
        return $this;
    }

    /**
     * Get isUsed
     *
     * @return boolean 
     */
    public function getIsUsed()
    {
        return $this->isUsed;
    }
    
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    } 
    
    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    // ---  
    // --- LIFECYCLECALLBACKS ACTIONS
    
    /**
    * @ORM\PrePersist
    */
    public function setCreatedAtValue() {
        if (!$this->getCreatedAt()) {
            $this->createdAt = new \DateTime();
            $this->setCulture( $this->getCulture().'_'.$this->getCountry()->getISO());
        }
    }    
    
    /**
    * @ORM\PreUpdate
    */
    public function setUpdatedAtValue() {
        $this->updatedAt = new \DateTime();        
        if(strlen($this->getCulture()) == 2)
            $this->setCulture( $this->getCulture().'_'.$this->getCountry()->getISO());
    }
}