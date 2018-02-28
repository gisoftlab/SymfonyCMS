<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\PagesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo,
    Symfony\Component\Validator\Constraints as Assert,
    Doctrine\ORM\Mapping as ORM,
    App\CoreBundle\Twig\FormaterExtension as Formater;

    /**
     * Category entity.
     *
     * @author Damian Ostraszewski <info@gisoft.pl>    
     * 
     * @Gedmo\Tree     
     * @ORM\Table(name="pages__category")
     * use repository for handy tree functions
     * @ORM\Entity(repositoryClass="App\PagesBundle\Repository\CategoryRepository")
     * @Gedmo\TranslationEntity(class="App\PagesBundle\Entity\CategoryTranslation")
     * @ORM\HasLifecycleCallbacks()
     */
class Category {

    const MENU = 1;
    const FOOTER = 2;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $name
     * @Gedmo\Translatable
     * @ORM\Column(name="name", type="string", length=100, nullable=true)     
     */
    private $name;

    /**
     * @var string $slug     
     * @Gedmo\Slug(fields={"name"})
     * @Gedmo\Translatable
     * @ORM\Column(name="slug", type="string", length=50, nullable=false, unique=true)
     */
    private $slug;

    /**
     * @var string $description
     * @Gedmo\Translatable
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var Page
     * 
     * @ORM\OneToMany(targetEntity="App\PagesBundle\Entity\Page", mappedBy="category", cascade={"persist"})
     */
    private $pages;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer")
     */
    private $root;



    /**
     * @var Category 
     * 
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="App\PagesBundle\Entity\Category", inversedBy="children" , cascade={"persist"})
     */
    private $parent;


    /**
     * @ORM\OneToMany(targetEntity="App\PagesBundle\Entity\Category", mappedBy="parent" , cascade="remove")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale = "pl";

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
     * Category constructor.
     */
    public function __construct() {
        $this->pages = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString() {
        $prefix = "";
        for ($i = 1; $i <= $this->lvl; $i++) {
            $prefix .= "-- ";
        }
        return $prefix . $this->getName();
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

    /**
     * @return string
     */
    public function getLaveledName() {
        return (string) $this;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLvl() {
        return $this->lvl;
    }

    /**
     * @param $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param $slug
     */
    public function setSlug($slug) {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @param $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param $parent
     */
    public function setParent($parent) {
        $this->parent = $parent;
    }

    /**
     * @return Category
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * @param Category $root
     */
    public function setRoot(Category $root) {
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
     * @param $locale
     */
    public function setTranslatableLocale($locale) {
        $this->locale = $locale;
    }

    /**
     * @ORM\PreRemove
     */
    public function preRemove() {
        /**
         * @var Category $child
         */
        foreach ($this->children as $child)
            $child->setParent(null);
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

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function makeSlug() {
        $parentSlug = "";
        $slug = Formater::slugify($this->getName());

        if ($this->getParent())
            $parentSlug .= $this->getParent()->getSlug() . '-' . $slug;
        else
            $parentSlug .= $slug;

        $this->setSlug($parentSlug);
    }

}
