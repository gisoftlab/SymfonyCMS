<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use App\CoreBundle\Annotations as CoreAnnotation;


/**
 * ProductOrders entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 *
 * @ORM\Table(name="products__orders")
 * @ORM\Entity(repositoryClass="App\ProductBundle\Repository\ProductOrdersRepository") 
 * @ORM\HasLifecycleCallbacks()
 */
class ProductOrders {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var product
     *
     * @ORM\ManyToOne(targetEntity="App\ProductBundle\Entity\Product" , inversedBy="orders", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id",  nullable=true)
     * })
     */
    private $product;

    /**
     * @var string $name             
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * 
     */
    private $name;

    /**
     * @var string $email
     * @Assert\Email(
     *     message = "The value '{{ value }}' is not a valid email."
     * )       
     * @ORM\Column(name="email", type="string",length=255, nullable=true) 
     */
    private $email;

    /**
     * @var string $dateFrom
     * @Assert\NotBlank()     
     * @Assert\Type("\DateTime")
     * @ORM\Column(name="date_from", type="datetime", nullable=true)
     */
    private $dateFrom;

    /**
     * @var string $dateTo
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     * @ORM\Column(name="date_to", type="datetime", nullable=true)
     */
    private $dateTo;

    /**
     * @var string $description
     * @ORM\Column(name="description", type="text", nullable=true)
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

    public function __construct() {}

    public function __toString() {
        return $this->getName();
    }

    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName() {
        return $this->name;
    }

    public function setProduct($product) {
        $this->product = $product;
    }

    public function getProduct() {
        return $this->product;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string $redirect
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set dateFrom
     *
     * @param string $dateFrom
     */
    public function setDateFrom($dateFrom) {
        $this->dateFrom = $dateFrom;
    }

    /**
     * Get dateFrom
     *
     * @return string $dateFrom
     */
    public function getDateFrom() {
        return $this->dateFrom;
    }

    /**
     * Set dateTo
     *
     * @param string $dateTo
     */
    public function setDateTo($dateTo) {
        $this->dateTo = $dateTo;
    }

    /**
     * Get dateTo
     *
     * @return string $dateTo
     */
    public function getDateTo() {
        return $this->dateTo;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string $description
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
