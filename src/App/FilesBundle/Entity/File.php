<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\FilesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use App\CoreBundle\Annotations as CoreAnnotation;

/**
 * File entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 *
 * @ORM\Table(name="files__file")
 * @ORM\Entity(repositoryClass="App\FilesBundle\Repository\FileRepository")  
 * @ORM\HasLifecycleCallbacks()
 */

class File {

    public static $filePaths = "uploads/";
    public static $waterMark = "water.png";
    public static $ImageDir = "Images";
    public static $FileDir = "Files";
    public static $FileTypeImages = 1;
    public static $FileTypeDocuments = 2;
    public static $FileTypeAudio = 3;
    public static $FileTypeVideo = 4;
    public static $FileTypeFiles = 5;
    public static $FileContextDefault = 'default';
    public static $FileContextPage = 'page';
    public static $FileContextProduct = 'product';

    public static function getFileContext() {
        return array(
            1 => self::$FileContextDefault, // Default package of files
            2 => self::$FileContextPage, // Page package of files
            3 => self::$FileContextProduct, // Page package of files
        );
    }

    public static function getType() {
        return array(
            'Images' => self::$FileTypeImages, // Images jpg jpeg png gif
            'Documents' => self::$FileTypeDocuments, // Documents doc txt xls pdf 
            'Audio' => self::$FileTypeAudio, // Audio  mp3
            'Video' => self::$FileTypeVideo, // Video avi
            'Files' => self::$FileTypeFiles, // Files others
        );
    }

    public static function getTypeName($name) {
        $arr = self::getType();
        return $arr[$name];
    }

    public static function getContentTypeDir($contentType) {

        // images files
        if (strpos($contentType, "image") !== false)
            return self::$FileTypeImages;
        // text files             
        if (strpos($contentType, "text") !== false)
            return self::$FileTypeDocuments;
        // audio files
        if (strpos($contentType, "audio") !== false)
            return self::$FileTypeAudio;
        // xls files
        if ($contentType == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
            return self::$FileTypeDocuments;
        // doc files
        if ($contentType == "application/msword")
            return self::$FileTypeDocuments;
        // swf files
        if ($contentType == "application/x-shockwave-flash")
            return self::$FileTypeVideo;

        return self::$FileTypeFiles;
    }

    public static function getPathRoot() {
        $docRoot = $_SERVER["DOCUMENT_ROOT"];
        $temp = explode("/", $docRoot);

        if ($temp[count($temp) - 1] != '')
            $result = $_SERVER["DOCUMENT_ROOT"] . "/";
        else
            $result = $_SERVER["DOCUMENT_ROOT"];

        if (strpos($_SERVER["DOCUMENT_ROOT"], "web") == false)
            $result.='web/';

        return $result;
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
     * @var string $element
     * 
     * @ORM\Column(name="element", type="string", length=255, nullable=true)
     */
    private $element;


    /**
     * @var thumbnail
     *
     * @ORM\OneToMany(targetEntity="App\PagesBundle\Entity\PageFiles", mappedBy="file" , cascade={"remove"})
     *
     */
    private $pageFiles;

    /**
     * @var thumbnail
     *
     * @ORM\OneToMany(targetEntity="App\FilesBundle\Entity\Thumbnail", mappedBy="file" , cascade={"remove"})
     * 
     */
    private $thumbnails;

    /**
     * @var string $context
     * 
     * @ORM\Column(name="context", type="string", length=255, nullable=true)
     */
    private $context;

    /**
     * @var string $fullPath
     * 
     * @ORM\Column(name="full_path", type="string", length=255, nullable=true)
     */
    private $fullPath;

    /**
     * @var string $filePath
     * 
     * @ORM\Column(name="file_path", type="string", length=255, nullable=true)
     */
    private $filePath;

    /**
     * @var string $path
     * 
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @var string $orginName
     * 
     * @ORM\Column(name="orgin_name", type="string", length=255, nullable=true)
     */
    private $orginName;

    /**
     * @var string $name     
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

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
     * @var int $sequence;
     * @CoreAnnotation\SetMaxSequance()
     * @ORM\Column(name="sequence", type="integer", nullable=false)
     */
    private $sequence = 0;

    /**
     * @var int $size
     * 
     * @ORM\Column(name="size", type="integer", nullable=false)
     */
    private $size = 0;

    /**
     * @var int $mimeType
     * 
     * @ORM\Column(name="mime_type", type="string", length=50, nullable=true)
     */
    private $mimeType;

    /**
     * @var int $fileType
     * 
     * @ORM\Column(name="file_type", type="integer", nullable=false)
     */
    private $fileType;

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
        $this->thumbnails = new ArrayCollection;
        $this->pageFiles = new ArrayCollection;
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
     * Set element
     *
     * @param string $element
     * @return File
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
     * Get Gallery
     *
     * @return null|ArrayCollection
     */
    public function getPageFiles() {
        return $this->pageFiles ? : $this->pageFiles = new ArrayCollection();
    }

    /**
     * @return ArrayCollection|null
     */
    public function showPageFiles() {
        return $this->getPageFiles();
    }

    /**
     * Sets the user Gallery
     *
     * @param array $files
     */
    public function setPageFiles(Array $files) {

        foreach ($files as $file) {
            $this->addPageFile($file);
        }
    }


    /**
     * @param File $file
     * @return $this
     */
    public function addPageFile(File $file) {
        if (!$this->getPageFiles()->contains($file)) {
            $this->getPageFiles()->add($file);
        }

        return $this;
    }

    /**
     * @param File $file
     * @return $this
     */
    public function removePageFile(File $file) {
        if ($this->getThumbnails()->contains($file)) {
            $this->getThumbnails()->removeElement($file);
        }

        return $this;
    }

    /**
     * Get Gallery
     *
     * @return null|ArrayCollection
     */
    public function getThumbnails() {
        return $this->thumbnails ? : $this->thumbnails = new ArrayCollection();
    }

    /**
     * @return ArrayCollection|null
     */
    public function showThumbnails() {
        return $this->getThumbnails();
    }

    /**
     * @param array $thumbnails
     */
    public function setThumbnails(Array $thumbnails) {

        foreach ($thumbnails as $thumbnail) {
            $this->addThumbnails($thumbnail);
        }
    }

    /**
     * @param Thumbnail $thumbnail
     * @return $this
     */
    public function addThumbnails(Thumbnail $thumbnail) {
        if (!$this->getThumbnails()->contains($thumbnail)) {
            $this->getThumbnails()->add($thumbnail);
        }

        return $this;
    }

    /**
     * @param Thumbnail $thumbnail
     * @return $this
     */
    public function removeThumbnails(Thumbnail $thumbnail) {
        if ($this->getThumbnails()->contains($thumbnail)) {
            $this->getThumbnails()->removeElement($thumbnail);
        }

        return $this;
    }

    /**
     * Set element
     *
     * @param string $context
     * @return $this
     */
    public function setContext($context) {
        $this->context = $context;

        return $this;
    }

    /**
     * Get context
     *
     * @return string 
     */
    public function getContext() {
        return $this->context;
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
     * Set filePath
     *
     * @param string $filePath
     * @return $this
     */
    public function setFilePath($filePath) {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get filePath
     *
     * @return string 
     */
    public function getFilePath() {
        return $this->filePath;
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
     * Set orginName
     *
     * @param string $orginName
     * @return $this
     */
    public function setOrginName($orginName) {
        $this->orginName = $orginName;

        return $this;
    }

    /**
     * Get orginName
     *
     * @return string 
     */
    public function getOrginName() {
        return $this->orginName;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
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
     * Set sequence
     *
     * @param string $sequence
     * @return $this
     */
    public function setSequence($sequence) {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get sequence
     *
     * @return string 
     */
    public function getSequence() {
        return $this->sequence;
    }

    /**
     * Set size
     *
     * @param string $size
     * @return $this
     */
    public function setSize($size) {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string 
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return $this
     */
    public function setMimeType($mimeType) {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string 
     */
    public function getMimeType() {
        return $this->mimeType;
    }

    /**
     * Set fileType
     *
     * @param string $fileType
     * @return $this
     */
    public function setFileType($fileType) {
        $this->fileType = $fileType;

        return $this;
    }

    /**
     * Get fileType
     *
     * @return string 
     */
    public function getFileType() {
        return $this->fileType;
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

}
