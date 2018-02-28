<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\PagesBundle\Entity;

use App\FilesBundle\Entity\File;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use App\CoreBundle\Annotations as CoreAnnotation;

/**
 * PageFiles entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>
 * 
 * @ORM\Table(name="pages__page_file")
 * @ORM\Entity(repositoryClass="App\PagesBundle\Repository\PageFilesRepository")  
 * @ORM\HasLifecycleCallbacks()
 */
class PageFiles {

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
     *
     * @ORM\ManyToOne(targetEntity="App\FilesBundle\Entity\File", inversedBy="pageFiles" , cascade={"persist","remove"})
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=false)
     */
    private $file;

    /**
     * @var Page
     *
     * @ORM\ManyToOne(targetEntity="App\PagesBundle\Entity\Page", inversedBy="gallery")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=false)
     */
    private $page;

    /**
     * @var integer $sequence     
     * @CoreAnnotation\SetMaxSequance()
     * @ORM\Column(name="sequence", type="integer", nullable=true)     
     */
    private $sequence = 0;

    public function __construct() {
        
    }

    public function getId() {
        return $this->id;
    }

    /**
     * Set File
     *
     * @param File $file
     */
    public function setFile(File $file) {
        $this->file = $file;
    }

    /**
     * Get File
     *
     * @return File $file
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * Set Page
     *
     * @param Page $page
     */
    public function setPage(Page $page) {
        $this->page = $page;
    }

    /**
     * Get Page
     *
     * @return Page $page
     */
    public function getPage() {
        return $this->page;
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

}
