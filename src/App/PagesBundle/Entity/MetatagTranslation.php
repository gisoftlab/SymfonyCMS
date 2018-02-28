<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\PagesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;


 /**
 * MetatagTranslation entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>    
 * 
 * @ORM\Entity(repositoryClass="App\PagesBundle\Repository\MetatagTranslationRepository")  
 * @ORM\Table(name="pages__metatag_translation",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * ) 
 * 
 * @ORM\HasLifecycleCallbacks()
 */
class MetatagTranslation extends AbstractPersonalTranslation
{
  
/**
     * Convinient constructor
     *
     * @param string $locale
     * @param string $field
     * @param string $value
     */
    public function __construct($locale, $field, $value)
    {
        $this->setLocale($locale);
        $this->setField($field);
        $this->setContent($value);
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\PagesBundle\Entity\Metatag", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
 
}