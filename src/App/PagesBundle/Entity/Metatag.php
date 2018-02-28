<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\PagesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

    /**
     * Metatag entity.
     *
     * @author Damian Ostraszewski <info@gisoft.pl>    
     * 
     * @ORM\Table(name="pages__metatag")
     * @ORM\Entity(repositoryClass="App\PagesBundle\Repository\MetatagRepository") 
     * @Gedmo\TranslationEntity(class="App\PagesBundle\Entity\MetatagTranslation")
     * @ORM\HasLifecycleCallbacks()
     */

class Metatag {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $title
     * @Gedmo\Translatable
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string $keywords 
     * @Gedmo\Translatable
     * @ORM\Column(name="keywords", type="string", length=255, nullable=true)
     */
    private $keywords;

    /**
     * @var string $description
     * @Gedmo\Translatable
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string $lang
     *
     * @ORM\Column(name="lang", type="string", length=255, nullable=true)
     */
    private $lang = "PL";

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale = "pl";

    /**
     * @ORM\OneToMany(
     *   targetEntity="App\PagesBundle\Entity\MetatagTranslation",
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

    public function __construct() {
        $this->translations = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getTranslations() {
        return $this->translations;
    }

    /**
     * @param MetatagTranslation $t
     */
    public function addTranslation(MetatagTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
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
     * Set keywords
     *
     * @param string $keywords
     */
    public function setKeywords($keywords) {
        $this->keywords = $keywords;
    }

    /**
     * Get keywords
     *
     * @return string $keywords
     */
    public function getKeywords() {
        return $this->keywords;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = strip_tags($description);
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
     * Set lang
     *
     * @param string $lang
     */
    public function setLang($lang) {
        $this->lang = $lang;
    }

    /**
     * Get lang
     *
     * @return string $idPageLang
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
