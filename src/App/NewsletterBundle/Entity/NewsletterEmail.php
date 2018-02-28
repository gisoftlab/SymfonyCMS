<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\NewsletterBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * NewsletterEmail entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>  
 *  
 * @ORM\Table(name="mewsletter__email")
 * @ORM\Entity(repositoryClass="App\NewsletterBundle\Repository\NewsletterEmailRepository")
 * @ORM\HasLifecycleCallbacks()
 */

class NewsletterEmail
{
    
     const STATUS_UNKNOWN = "unknown";          
     const TYPE_DEFAULT   = "default";
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;                
    
    /**
     * @var string $email
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    protected $email;
                    
     /**
     * @var string $IP
     *     
     * @ORM\Column(name="ip", type="string", length=128, unique=false, nullable=true)
     */
    protected $IP = 0;
            
    
    /**
     * @var string $type 
     * @ORM\Column(name="type", type="string",length=50, nullable=false)
     */
    protected $type = "DEFAULT";     
    
    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;
    

    public function __construct()
    {
        
    }
    
    
     public function getId()
    {
        return $this->id;
    }

    public function getLvl()
    {
        return $this->lvl;
    }
            
    /**
    * @param string $from
    */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
    * @return string
    */
    public function getEmail()
    {
        return $this->email;
    }
       
    
    /**
     * @param integer $IP
     */
    public function setIP($IP) {
        $this->IP = $IP;
    }

    /**
     * @return integer
     */
    public function getIP() {
        return $this->IP;
    }
        
    
    /**
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }
  
    
    /**
     * Sets the creation date
     *
     * @param \DateTime|null $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt = null) {
        $this->createdAt = $createdAt;
    }

    /**
     * Returns the creation date
     *
     * @return \DateTime|null
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }
   

    // ---  
    // --- LIFECYCLECALLBACKS ACTIONS
    
    /**
    * @ORM\PrePersist
    */
    public function setCreatedAtValue() {
        if (!$this->getCreatedAt()) {
            $this->createdAt = new \DateTime();
        }
    }

    
}