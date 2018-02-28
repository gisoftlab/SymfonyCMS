<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\FilesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\CoreBundle\Annotations as CoreAnnotation;

/**
 * Thumbnail entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 *
 * @ORM\Table(name="files__thumbnail")
 * @ORM\Entity(repositoryClass="App\FilesBundle\Repository\ThumbnailRepository")  
 * @ORM\HasLifecycleCallbacks()
 */

class Thumbnail {

    public static $ImageSizeOriginal = "original";
    public static $ImageSizeBig = "big";
    public static $ImageSizeMiddel = "middel";
    public static $ImageSizeSmall = "small";
    public static $ImageSizeSmaller = "smaller";
    public static $ImageSizeCrop1 = "crop1";
    public static $ImageSizeRotate = "rotate";

    /**
     * @return array $status
     */
    public static function getImageSizeStatus() {
        $status = array(
            1 => self::$ImageSizeOriginal,
            2 => self::$ImageSizeBig,
            3 => self::$ImageSizeMiddel,
            4 => self::$ImageSizeSmall,
            5 => self::$ImageSizeSmaller,
            6 => self::$ImageSizeCrop1,
            7 => self::$ImageSizeRotate,
        );

        return $status;
    }

    /**
     * @param $name
     * @return bool|int|string
     */
    public static function getImageSizeID($name) {
        $arr = self::getImageSizeStatus();

        foreach ($arr as $key => $value) {
            if ($value == $name)
                return $key;
        }

        return false;
    }

    /**
     * @return array $status
     */
    public static function getListImageSize() {
        $status = array(
            self::$ImageSizeOriginal => array("width" => 0, "height" => 0),
            self::$ImageSizeBig => array("width" => 600, "height" => 480),
            self::$ImageSizeMiddel => array("width" => 480, "height" => 300),
            self::$ImageSizeSmall => array("width" => 200, "height" => 0),
            self::$ImageSizeSmaller => array("width" => 120, "height" => 0),
            self::$ImageSizeCrop1 => array("width" => 160, "height" => 120),
        );

        return $status;
    }

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
     * @ORM\ManyToOne(targetEntity="\App\FilesBundle\Entity\File", inversedBy="thumbnails")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=false)
     */
    private $file;

    /**
     * @var string $element
     * 
     * @ORM\Column(name="element", type="string", length=255, nullable=true)
     */
    private $element;

    /**
     * @var string $fullPath
     * 
     * @ORM\Column(name="full_path", type="string", length=255, nullable=true)
     */
    private $fullPath;

    /**
     * @var string $path
     * 
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @var string $title
     * 
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string $extention
     * 
     * @ORM\Column(name="extention", type="string", length=10, nullable=true)
     */
    private $extention;

    /**
     * @var int $width
     * 
     * @ORM\Column(name="width", type="integer", nullable=false)
     */
    private $width = 0;

    /**
     * @var int $height
     * 
     * @ORM\Column(name="height", type="integer", nullable=false)
     */
    private $height = 0;

    /**
     * @var int $cropped
     * 
     * @ORM\Column(name="cropped", type="integer", nullable=false)
     */
    private $cropped = 0;

    /**
     * @var int $original
     * 
     * @ORM\Column(name="original", type="integer", nullable=false)
     */
    private $original = 0;

    /**
     * @var int $status
     * 
     * @ORM\Column(name="status", type="string", length=100, nullable=true)
     */
    private $status ;

    public function __construct() {
        
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set file
     *
     * @param \App\FilesBundle\Entity\File $file
     * @return $this
     */
    public function setFile(File $file) {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \App\FilesBundle\Entity\File
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * Set element
     *
     * @param string $element
     * @return $this
     */
    public function setElement($element) {
        $this->element = $element;

        return $this;
    }

    /**
     * Get element
     *
     * @return string 
     */
    public function getElement() {
        return $this->element;
    }

    /**
     * Set fullPath
     *
     * @param string $fullPath
     * @return $this
     */
    public function setFullPath($fullPath) {
        $this->fullPath = $fullPath;

        return $this;
    }

    /**
     * Get fullPath
     *
     * @return string 
     */
    public function getFullPath() {
        return $this->fullPath;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return $this
     */
    public function setPath($path) {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set extention
     *
     * @param string $extention
     * @return $this
     */
    public function setExtention($extention) {
        $this->extention = $extention;

        return $this;
    }

    /**
     * Get extention
     *
     * @return string 
     */
    public function getExtention() {
        return $this->extention;
    }

    /**
     * Set width
     *
     * @param string $width
     * @return $this
     */
    public function setWidth($width) {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return string 
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param string $height
     * @return $this
     */
    public function setHeight($height) {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return string 
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * Set cropped
     *
     * @param string $cropped
     * @return $this
     */
    public function setCropped($cropped) {
        $this->cropped = $cropped;

        return $this;
    }

    /**
     * Get cropped
     *
     * @return string 
     */
    public function getCropped() {
        return $this->cropped;
    }

    /**
     * Set original
     *
     * @param string $original
     * @return $this
     */
    public function setOriginal($original) {
        $this->original = $original;

        return $this;
    }

    /**
     * Get original
     *
     * @return string 
     */
    public function getOriginal() {
        return $this->original;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus() {
        return $this->status;
    }

}