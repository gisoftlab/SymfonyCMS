<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\PagesBundle\Entity;

use App\FilesBundle\Entity\File;
use App\ProductBundle\Entity\Product;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use App\CoreBundle\Annotations as CoreAnnotation;
use App\CoreBundle\Twig\FormaterExtension as Formater;
use Gedmo\Translatable\Translatable;



/**
 * Page entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 *
 * @Gedmo\Tree(type="nested")
 *
 * @ORM\Table(name="pages__page")
 * @ORM\Entity(repositoryClass="App\PagesBundle\Repository\PageRepository") 
 * @Gedmo\TranslationEntity(class="App\PagesBundle\Entity\PageTranslation")
 * @ORM\HasLifecycleCallbacks()
 */

class Page implements Translatable{

    const PAGES_PUBLISHED = 1;
    const PAGES_UNPUBLISHED = 0;
    const PAGES_MAIN_PARENT = 0;
    const DEFAULT_LANG = "PL";
    const ROOT = 1;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    protected $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    protected $lvl;


    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    protected $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    protected $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="App\PagesBundle\Entity\Page", inversedBy="children", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent = null;

    /**
     * @ORM\OneToMany(targetEntity="App\PagesBundle\Entity\Page", mappedBy="parent", cascade="remove", fetch="EXTRA_LAZY" )
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @var File
     * 
     * @ORM\ManyToOne(targetEntity="App\FilesBundle\Entity\File",  cascade={"remove"} )
     * @ORM\JoinColumn(name="icon_id", referencedColumnName="id", nullable=true)
     */
    private $icon;

    /**
     * @var pageFiles
     *           
     * @ORM\OneToMany(
     *   targetEntity="App\PagesBundle\Entity\PageFiles",
     *   mappedBy="page",
     *   cascade={"persist", "remove"},
     *   fetch="EXTRA_LAZY"
     * )          
     */
    private $gallery;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="App\PagesBundle\Entity\Category" , inversedBy="pages", cascade={"persist"}, fetch="EXTRA_LAZY" )
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id",   nullable=true)
     * })
     */
    private $category;

    /**
     * @var Product
     *
     * @ORM\OneToMany(targetEntity="App\ProductBundle\Entity\Product", mappedBy="page", fetch="EXTRA_LAZY")
     * 
     */
    private $products;

    /**
     * @var integer $idPageLang
     *               
     * @ORM\OneToMany(targetEntity="App\PagesBundle\Entity\Page", mappedBy="pageLang")     
     * @ORM\Column(name="id_page_lang", type="integer", nullable=true)
     * @ORM\OrderBy({"lang" = "ASC"})
     */
    private $idPageLang;

    /**
     * @var string $title
     *
     * @Gedmo\Translatable    
     * @ORM\Column(name="title", type="string", length=255, nullable=true)     
     */
    private $title;

    /**
     * @var string $redirect
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="redirect", type="string", length=255, nullable=true)
     */
    private $redirect;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @Gedmo\Translatable
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @var string $short
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="short", type="text", nullable=true)
     */
    private $short;

    /**
     * @var string $uri
     * @ORM\Column(name="uri", type="string", length=255,nullable=true)
     */
    private $uri;

    /**
     * @var string $description
     * @Gedmo\Translatable
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
     * @var integer $sequence     
     * @CoreAnnotation\SetMaxSequance()
     * @ORM\Column(name="sequence", type="integer", nullable=true)     
     */
    private $sequence = 0;

    /**
     * @var string $metaDescription
     * @Gedmo\Translatable
     * @ORM\Column(name="meta_description", type="string", length=255, nullable=true)
     */
    private $metaDescription;

    /**
     * @var string $metaKeywords
     * @Gedmo\Translatable
     * @ORM\Column(name="meta_keywords", type="string", length=255, nullable=true)
     */
    private $metaKeywords;

    /**
     * @var string $metaTitle
     * @Gedmo\Translatable
     * @ORM\Column(name="meta_title", type="string", length=255, nullable=true)
     */
    private $metaTitle;

    /**
     * @var string $lang
     *
     * @ORM\Column(name="lang", type="string", length=255, nullable=true)
     */
    private $lang = "PL";

    /**
     * @ORM\OneToMany(
     *   targetEntity="App\PagesBundle\Entity\PageTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

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

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;

    public function __construct() {
        $this->gallery = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    public function __toString() {
        $prefix = "";
        for ($i = 1; $i <= $this->lvl; $i++) {
            $prefix .= "-- ";
        }
        return $prefix . $this->getTitle();
    }

    public function getTranslations() {
        return $this->translations;
    }

    public function addTranslation(PageTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getLvl() {
        return $this->lvl;
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
     * Set redirect
     *
     * @param string $redirect
     */
    public function setRedirect($redirect) {
        $this->redirect = $redirect;
    }

    /**
     * Get redirect
     *
     * @return string $redirect
     */
    public function getRedirect() {
        return $this->redirect;
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
     * Set uri
     *
     * @param string $uri
     */
    public function setUri($uri) {
        $this->uri = $uri;
    }

    /**
     * Get short
     *
     * @return string $uri
     */
    public function getUri() {
        return $this->uri;
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
     * @param $parent
     */
    public function setParent($parent) {
        $this->parent = $parent;
    }

    /**
     * @return Page
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * @param Page $root
     */
    public function setRoot(Page $root) {
        $this->root = $root;
    }

    /**
     * @return mixed
     */
    public function getRoot() {
        return $this->root;
    }

    /**
     * @param $lft
     * @return $this
     */
    public function setLft($lft) {
        $this->lft = $lft;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLft() {
        return $this->lft;
    }

    /**
     * @param $lvl
     * @return $this
     */
    public function setLvl($lvl) {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * @param $rgt
     * @return $this
     */
    public function setRgt($rgt) {
        $this->rgt = $rgt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRgt() {
        return $this->rgt;
    }

    /**
     * @return mixed
     */
    public function getChildren() {
        return $this->children;
    }

    /**
     * Set File
     *
     * @param \App\FilesBundle\Entity\File $file
     */
    public function setIcon($file) {
        $this->icon = $file;
    }

    /**
     * Get File
     *
     * @return \App\FilesBundle\Entity\File
     */
    public function getIcon() {
        return $this->icon;
    }

    /**
     * Get Gallery
     *
     * @return null|ArrayCollection
     */
    public function getGallery() {
        return $this->gallery ? : $this->gallery = new ArrayCollection();
    }

    public function showGallery() {
        return $this->getGallery();
    }

    /**
     * Sets the user Gallery
     *
     * @param array $files
     */
    public function setGallery(Array $files) {

        foreach ($files as $file) {
            $this->addGallery($file);
        }
    }

    /**
     * Add Gallery
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
     * Remove Gallery
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
     * Get Products
     *
     * @return null|ArrayCollection
     */
    public function getProducts() {
        return $this->products ? : $this->products = new ArrayCollection();
    }

    /**
     * Show Products
     *
     * @return ArrayCollection
     */
    public function showProducts() {
        return $this->getProducts();
    }

    /**
     * Set Product
     *
     * @param array $products
     */
    public function setProduct(Array $products) {
        foreach ($products as $product) {
            $this->addProduct($product);
        }
    }

    /**
     * Add Product
     *
     * @param \App\ProductBundle\Entity\Product $product
     * @return $this
     */
    public function addProduct(Product $product) {
        if (!$this->getProducts()->contains($product)) {
            $this->getProducts()->add($product);
        }

        return $this;
    }

    /**
     * Remove product
     *
     * @param \App\ProductBundle\Entity\Product $product
     * @return $this
     */
    public function removeProduct(Product $product) {
        if ($this->getProducts()->contains($product)) {
            $this->getProducts()->removeElement($product);
        }

        return $this;
    }

    /**
     * Set category
     *
     * @param null|\App\PagesBundle\Entity\Category $category
     */
    public function setCategory($category) {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return null|\App\PagesBundle\Entity\Category $category
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * Set idPageLang
     *
     * @param Page $idPageLang
     */
    public function setIdPageLang(Page $idPageLang) {
        $this->idPageLang = $idPageLang;
    }

    /**
     * Get idPageLang
     *
     * @return boolean $idPageLang
     */
    public function getIdPageLang() {
        return $this->idPageLang;
    }

    /**
     * Set metaKeywords
     *
     * @param boolean $metaKeywords
     */
    public function setMetaKeywords($metaKeywords) {
        $this->metaKeywords = $metaKeywords;
    }

    /**
     * Get metaKeywords
     *
     * @return boolean $metaKeywords
     */
    public function getMetaKeywords() {
        return $this->metaKeywords;
    }

    /**
     * Set metaDescription
     *
     * @param boolean $metaDescription
     */
    public function setMetaDescription($metaDescription) {
        $this->metaDescription = $metaDescription;
    }

    /**
     * Get metaDescription
     *
     * @return boolean $metaDescription
     */
    public function getMetaDescription() {
        return $this->metaDescription;
    }

    /**
     * Set metaTitle
     *
     * @param boolean $metaTitle
     */
    public function setMetaTitle($metaTitle) {
        $this->metaTitle = $metaTitle;
    }

    /**
     * Get sequence
     *
     * @return boolean $idPageLang
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
     * Set lang
     *
     * @param boolean $lang
     */
    public function setLang($lang) {
        $this->lang = $lang;
    }

    /**
     * Get lang
     *
     * @return boolean $idPageLang
     */
    public function getLang() {
        return $this->lang;
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

    public function makeSlug() {
        $parentSlug = "";

        $slug = Formater::slugify($this->getTitle());

        if($this->getSlug() == $slug || !$this->getSlug()) {
            if ($this->getParent() && $this->getParent()->getId() != \Web\PagesBundle\Statics\Page::PAGE_ROOT) {
                $parentSlug .= $this->getParent()->getSlug().'-'.$slug;
            } else {
                $parentSlug .= $slug;
            }

            $this->setSlug($parentSlug);
        }
    }

    /**
     * @ORM\PreRemove
     * Release all the children on remove
     */
    public function preRemove() {
        /**
         * @var Page $child
         */
        foreach ($this->children as $child)
            $child->setParent(null);

        $this->setCategory(null);
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

    public function setTranslatableLocale($locale) {
        $this->locale = $locale;
    }

}
