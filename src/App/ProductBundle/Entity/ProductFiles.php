<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\ProductBundle\Entity;

use App\FilesBundle\Entity\File;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use App\CoreBundle\Annotations as CoreAnnotation;


/**
 * ProductFiles entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>    
 *
 * @ORM\Table(name="products__product_file")
 * @ORM\Entity(repositoryClass="App\ProductBundle\Repository\ProductFilesRepository")  
 * @ORM\HasLifecycleCallbacks()
 */

class ProductFiles {

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
     * @ORM\ManyToOne(targetEntity="App\FilesBundle\Entity\File")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=false)
     */
    private $file;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="App\ProductBundle\Entity\Product", inversedBy="gallery")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     */
    private $product;

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
     * @param \App\FilesBundle\Entity\File $file
     */
    public function setFile(File $file) {
        $this->file = $file;
    }

    /**
     * Get File
     *
     * @return File
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * Set Product
     *
     * @param \App\ProductBundle\Entity\Product $product
     */
    public function setProduct(Product $product) {
        $this->product = $product;
    }

    /**
     * Get Product
     *
     * @return \App\ProductBundle\Entity\Product $product
     */
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

}
