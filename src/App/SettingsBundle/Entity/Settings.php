<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\SettingsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\CoreBundle\Annotations as CoreAnnotation;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Languages entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>  
 *
 * @ORM\Table(name="settings__app")
 * @ORM\Entity(repositoryClass="App\SettingsBundle\Repository\SettingsRepository")   
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("name")
 */
class Settings {

    const SET_SLOGAN = "slogan";
    const SET_COMPANY_NAME = "company_name";
    const SET_COMPANY_HOST = "company_host";
    const SET_FIRST_NAME = "first_name";
    const SET_LAST_NAME = "last_name";
    const SET_EMAIL_MAIN = "email_main";
    const SET_EMAIL = "email";
    const SET_PHONE = "phone";
    const SET_CULTURE = "culture";
    const SET_LANG = "lang";

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $type
     * 
     * @ORM\Column(name="domain", type="string", length=50, nullable=true)
     */
    private $type;

    /**
     * @var string $name
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255, nullable=false , unique=true)
     */
    private $name;

    /**
     * @var string $value
     * 
     * @ORM\Column(name="value", type="string", length=255, nullable=true)
     */
    private $value;

    /**
     * @var string $description
     * 
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

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

    public function __construct() {
        
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return $this
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return $this
     */
    public function setValue($value) {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
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
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue() {
        $this->updatedAt = new \DateTime();
    }

}
