<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\ProductBundle\Entity;

use App\FilesBundle\Entity\File;
use App\PagesBundle\Entity\Page;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use App\CoreBundle\Annotations as CoreAnnotation;
use App\CoreBundle\Twig\FormaterExtension as Formater;

/**
 * Product entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>    
 * 
 * @ORM\Table(name="products__product")
 * @ORM\Entity(repositoryClass="App\ProductBundle\Repository\ProductRepository")  
 * @ORM\HasLifecycleCallbacks()
 */

class Product {

    const PROMOTED = 3;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var File
     * @ORM\ManyToOne(targetEntity="App\FilesBundle\Entity\File",  cascade={"persist"})
     * @ORM\JoinColumn(name="icon_id", referencedColumnName="id", nullable=true)
     */
    private $icon;

    /**
     * @var File
     *
     * @ORM\ManyToOne(targetEntity="App\FilesBundle\Entity\File",  cascade={"persist"})
     * @ORM\JoinColumn(name="icon_promoted_id", referencedColumnName="id", nullable=true)
     */
    private $iconPromoted;

    /**
     * @var ProductFiles
     *
     * @ORM\OneToMany(targetEntity="App\ProductBundle\Entity\ProductFiles", mappedBy="product")
     * 
     */
    private $gallery;

    /**
     * @var Page
     *
     * @ORM\ManyToOne(targetEntity="App\PagesBundle\Entity\Page", inversedBy="products",  cascade={"persist"})
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=true)
     */
    private $page;

    /**
     * @var string $title       
     * @Assert\Length(
     *      max = "255",
     *      maxMessage = "Initial weight lbs cannot be longer than {{ limit }} characters length"
     * ) 
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})     
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @var string $short
     * @Assert\Length(
     *      max = "255",
     *      maxMessage = "Initial weight lbs cannot be longer than {{ limit }} characters length"
     * ) 
     * @ORM\Column(name="short", type="text", nullable=true)
     */
    private $short;

    /**
     * @var string $description

     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var boolean $published
     *
     * @ORM\Column(name="published", type="boolean", nullable=true)
     */
    private $published = true;

    /**
     * @var boolean $promoted
     *
     * @ORM\Column(name="promoted", type="boolean", nullable=true)
     */
    private $promoted = false;

    /**
     * @var float $price
     * 
     * @Assert\Range(
     *      min = 0,
     *      max = 1000000,
     *      minMessage = "This value should be {{ limit }} or more",
     *      maxMessage = "This value should be {{ limit }} or less"
     * )
     * @ORM\Column(name="price", type="decimal", scale=2, nullable=true)
     */
    private $price = 0;

    /**
     * @var float $deposit
     * @Assert\Range(
     *      min = 0,
     *      max = 1000000,
     *      minMessage = "This value should be {{ limit }} or more",
     *      maxMessage = "This value should be {{ limit }} or less"
     * )
     * @ORM\Column(name="deposit", type="decimal", scale=2, nullable=true)
     */
    private $deposit = 0;

    /**
     * @var integer $viewed
     * @Assert\Range(
     *      min = 0,
     *      max = 10000,
     *      minMessage = "This value should be {{ limit }} or more",
     *      maxMessage = "This value should be {{ limit }} or less"
     * )
     * @Assert\Regex("/^(?:0|[1-9]\d*)$/")
     * @Assert\Type(type="integer")
     * @ORM\Column(name="viewed", type="integer", nullable=true)     
     */
    private $viewed = 0;

    /**
     * @var integer $sequence     
     * @CoreAnnotation\SetMaxSequance()
     * @ORM\Column(name="sequence", type="integer", nullable=true)     
     */
    private $sequence = 0;

    /**
     * @var string $metaDescription   
     * @ORM\Column(name="meta_description", type="string", length=255, nullable=true)
     */
    private $metaDescription;

    /**
     * @var string $metaKeywords   
     * @ORM\Column(name="meta_keywords", type="string", length=255, nullable=true)
     */
    private $metaKeywords;

    /**
     * @var string $metaTitle  
     * @Assert\Length(
     *      max = "255",
     *      maxMessage = "Initial weight lbs cannot be longer than {{ limit }} characters length"
     * ) 
     * @ORM\Column(name="meta_title", type="string", length=255, nullable=true)
     */
    private $metaTitle;

    /**
     * @ORM\OneToMany(
     *   targetEntity="App\ProductBundle\Entity\ProductItems",
     *   mappedBy="product",
     *   cascade={"persist"}
     * )
     * @ORM\OrderBy({"sequence" = "ASC"})
     */
    private $items;

    /**
     * @ORM\OneToMany(
     *   targetEntity="App\ProductBundle\Entity\ProductOrders",
     *   mappedBy="product",
     *   cascade={"persist"}     
     * )
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $orders;

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
        $this->gallery = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function __toString() {
        return $this->getTitle();
    }

    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set short
     *
     * @param string $short
     */
    public function setShort($short) {
        $this->short = $short;
    }

    /**
     * Get short
     *
     * @return string $short
     */
    public function getShort() {
        return $this->short;
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
     * Set slug
     *
     * @param boolean $slug
     */
    public function setSlug($slug) {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return boolean $slug
     */
    public function getSlug() {
        return $this->slug;
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
     * Set promoted
     *
     * @param $promoted
     * @internal param bool $published
     */
    public function setPromoted($promoted) {
        $this->promoted = $promoted;
    }

    /**
     * Get promoted
     *
     * @return boolean $promoted
     */
    public function getPromoted() {
        return $this->promoted;
    }

    /**
     * Set price
     *
     * @param float $price
     */
    public function setPrice($price) {
        $this->price = $price;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * Set deposit
     *
     * @param float $deposit
     */
    public function setDeposit($deposit) {
        $this->deposit = $deposit;
    }

    /**
     * Get deposit
     *
     * @return float
     */
    public function getDeposit() {
        return $this->deposit;
    }

    /**
     * Set viewed
     *
     * @param float $viewed
     */
    public function setViewed($viewed) {
        $this->viewed = $viewed;
    }

    /**
     * Get viewed
     *
     * @return float $viewed
     */
    public function getViewed() {
        return $this->viewed;
    }

    /**
     * Set File
     *
     * @param \App\FilesBundle\Entity\file $file
     */
    public function setIcon($file) {
        $this->icon = $file;
    }

    /**
     * Get File
     *
     * @return \App\FilesBundle\Entity\file $file
     */
    public function getIcon() {
        return $this->icon;
    }

    /**
     * Set File
     *
     * @param \App\FilesBundle\Entity\file $file
     */
    public function setIconPromoted($file) {
        $this->iconPromoted = $file;
    }

    /**
     * Get File
     *
     * @return \App\FilesBundle\Entity\file $file
     */
    public function getIconPromoted() {
        return $this->iconPromoted;
    }

    /**
     * Get Gallery
     *
     * @return null|ArrayCollection
     */
    public function getGallery() {
        return $this->gallery ? : $this->gallery = new ArrayCollection();
    }

    /**
     * Show
     *
     * @return ArrayCollection|null
     */
    public function showGallery() {
        return $this->getGallery();
    }

    /**
     * Set gallery
     *
     * @param array $files
     */
    public function setGallery(Array $files) {

        foreach ($files as $file) {
            $this->addGallery($file);
        }
    }

    /**
     * Add gallery
     *
     * @param \App\FilesBundle\Entity\File $file
     * @return $this
     */
    public function addGallery(File $file) {
        if (!$this->getGallery()->contains($file)) {
            $this->getGallery()->add($file);
        }

        return $this;
    }

    /**
     * remove gallery
     *
     * @param \App\FilesBundle\Entity\File $file
     * @return $this
     */
    public function removeGallery(File $file) {
        if ($this->getGallery()->contains($file)) {
            $this->getGallery()->removeElement($file);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getItems() {
        return $this->items ? : $this->items = new ArrayCollection();
    }

    /**
     * Sets the user Items
     *
     * @param array $items
     */
    public function setItem(Array $items) {

        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    /**
     * Add a order
     *
     * @param ProductItems $item
     * @return $this
     */
    public function addItem(ProductItems $item) {
        if (!$this->getItems()->contains($item)) {
            $this->getItems()->add($item);
        }

        return $this;
    }


    /**
     * @param ProductItems $item
     * @return $this
     */
    public function removeItem(ProductItems $item) {
        if ($this->getItems()->contains($item)) {
            $this->getItems()->removeElement($item);
        }

        return $this;
    }

    /**
     * @return null|ArrayCollection
     */
    public function getOrders() {
        return $this->orders ? : $this->orders = new ArrayCollection();
    }

    /**
     * @return ArrayCollection|null
     */
    public function showOrders() {
        return $this->getOrders();
    }

    /**
     * @param array $orders
     */
    public function setOrder(Array $orders) {

        foreach ($orders as $order) {
            $this->addOrder($order);
        }
    }

    /**
     * @param ProductOrders $order
     * @return $this
     */
    public function addOrder(ProductOrders $order) {
        if (!$this->getOrders()->contains($order)) {
            $this->getOrders()->add($order);
        }

        return $this;
    }

    /**
     * Set page
     *
     * @param \App\PagesBundle\Entity\Page $page
     */
    public function setPage($page) {
        $this->page = $page;
    }

    /**
     * Get page
     *
     * @return Page
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * Set metaKeywords
     *
     * @param string $metaKeywords
     */
    public function setMetaKeywords($metaKeywords) {
        $this->metaKeywords = $metaKeywords;
    }

    /**
     * Get metaKeywords
     *
     * @return string
     */
    public function getMetaKeywords() {
        return $this->metaKeywords;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     */
    public function setMetaDescription($metaDescription) {
        $this->metaDescription = $metaDescription;
    }

    /**
     * Get metaDescription
     *
     * @return string $metaDescription
     */
    public function getMetaDescription() {
        return $this->metaDescription;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     */
    public function setMetaTitle($metaTitle) {
        $this->metaTitle = $metaTitle;
    }

    /**
     * Get sequence
     *
     * @return string
     */
    public function getMetaTitle() {
        return ($this->metaTitle) ? $this->metaTitle : $this->getTitle();
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
    public function setUpdatedAt(\DateTime $updatedAt) {
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
     * @ORM\PreUpdate
     */
    public function makeSlug() {
        $parentSlug = "";
        $slug = Formater::slugify($this->getTitle());

        if ($this->getPage())
            $parentSlug .= $this->getPage()->getSlug() . '-' . $slug;
        else
            $parentSlug .= $slug;

        $this->setSlug($parentSlug);
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

    public function setViwedValue() {
        $this->viewed++;
    }

}
