<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\TestimonialBundle\Entity;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Testimonial entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>  
 *  
 * @ORM\Table(name="testimonial")
 * @ORM\Entity(repositoryClass="App\TestimonialBundle\Repository\TestimonialRepository")
 * @ORM\HasLifecycleCallbacks()
 */

class Testimonial
{
    
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
     * @var string $name
     *     
     * @ORM\Column(name="name", type="string", length=128, unique=false, nullable=true)
     */
    protected $name;

    /**
     * @var string $city
     *
     * @ORM\Column(name="city", type="string", length=128, unique=false, nullable=true)
     */
    protected $city;

    /**
     * @var string $message
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

    /**
     * @var boolean $published
     *
     * @ORM\Column(name="published", type="boolean", nullable=true)
     */
    private $published = false;
    
    /**
     * @var DateTime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;
    

    public function __construct()
    {
        
    }
    
    
     public function getId()
    {
        return $this->id;
    }
            
    /**
    * @param string $email
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
     * @param integer $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return integer
     */
    public function getName() {
        return $this->name;
    }
        
    
    /**
     * @param string $city
     */
    public function setCity($city) {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @param string $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * Set published
     *
     * @param boolean $published
     */
    public function setPublished($published) {
        $this->published = $published;
    }

    /**
     * Get published
     *
     * @return boolean $published
     */
    public function getPublished() {
        return $this->published;
    }
  
    
    /**
     * Sets the creation date
     *
     * @param DateTime|null $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt = null) {
        $this->createdAt = $createdAt;
    }

    /**
     * Returns the creation date
     *
     * @return DateTime|null
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }


    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt() {
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
            $this->updatedAt = new \DateTime();
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue() {
        $this->updatedAt = new \DateTime();
    }

    
}