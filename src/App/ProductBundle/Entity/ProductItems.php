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
 * ProductItems entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 *
 * @ORM\Table(name="products__items")
 * @ORM\Entity(repositoryClass="App\ProductBundle\Repository\ProductItemsRepository") 
 * @ORM\HasLifecycleCallbacks()
 */

class ProductItems {

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
     * @ORM\ManyToOne(targetEntity="App\ProductBundle\Entity\Product" , inversedBy="items", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id",   nullable=true)
     * })
     */
    private $product;

    /**
     * @var string $field     
     * @ORM\Column(name="field", type="string", length=255, nullable=true)
     */
    private $field;

    /**
     * @var string $content
     * @ORM\Column(name="content", type="string", nullable=true)
     */
    private $content;

    /**
     * @var string $description
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer $sequence     
     * @CoreAnnotation\SetMaxSequance()
     * @ORM\Column(name="sequence", type="integer", nullable=true)     
     */
    private $sequence = 0;

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

    public function getId() {
        return $this->id;
    }

    /**
     * Set field
     *
     * @param string $field
     */
    public function setField($field) {
        $this->field = $field;
    }

    /**
     * Get field
     *
     * @return string $field
     */
    public function getField() {
        return $this->field;
    }

    /**
     * Set content
     *
     * @param string $content
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return string $content
     */
    public function getContent() {
        return $this->content;
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

    public function setProduct($product) {
        $this->product = $product;
    }

    public function getProduct() {
        return $this->product;
    }

    /**
     * Set sequence
     *
     * @param boolean $sequence
     */
    public function setSequence($sequence) {
        $this->sequence = $sequence;
    }

    /**
     * Get sequence
     *
     * @return boolean $idPageLang
     */
    public function getSequence() {
        return $this->sequence;
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
