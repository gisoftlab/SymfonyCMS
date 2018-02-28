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
 * PageTranslation entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>
 * 
 * @ORM\Entity(repositoryClass="App\PagesBundle\Repository\PageTranslationRepository")  
 * @ORM\Table(name="pages__page_translation",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * ) 
 * 
 * @ORM\HasLifecycleCallbacks()
 */


class PageTranslation extends AbstractPersonalTranslation
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
     * @ORM\ManyToOne(targetEntity="App\PagesBundle\Entity\Page", inversedBy="translations",fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
 
}