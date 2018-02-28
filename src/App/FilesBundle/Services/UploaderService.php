<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\FilesBundle\Services;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use App\FilesBundle\Entity\File;
use App\FilesBundle\Entity\Thumbnail;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\Box;
use Imagine\Image\Point;

/**
 * Uploader Service
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 */
class UploaderService {

    protected $imagine;
    protected $container;
    protected $request;
    protected $em;
    protected $namespace = 'AppFilesBundle';

    /**
     * UploaderService constructor.
     * @param RequestStack $request
     * @param RegistryInterface $doctrine
     * @param ContainerInterface $container
     */
    public function __construct(RequestStack $request, RegistryInterface $doctrine, ContainerInterface $container) {
        $this->request = $request->getMasterRequest();
        $this->container = $container;
        $this->em = $doctrine->getEntityManager();

        $this->imagine = $container->get('files_imagine');
    }
  
    /**
    * GET Request
    *
    * @return null|\Symfony\Component\HttpFoundation\Request
    */
    public function getRequest() {
        return $this->request;
    }

    /**
    * GET Container
    *
    * @return ContainerInterface
    */
    public function getContainer() {
        return $this->container;
    }

    /**
    * Uploader files
    *
    * @param UploadedFile $file
    * @param $title
    * @param $pagesId
    * @param null $context
    * @param bool $waterMark
    * @return File
    * @throws \Exception
    */
    public function Upload(UploadedFile $file, $title, $pagesId, $context = null, $waterMark = false) {
        if (!$context)
            $context = File::$FileContextDefault;

        $filePaths = $this->container->getParameter("app_files.filePath");
        $imageDir = $this->container->getParameter("app_files.ImageDir");
        $fileDir = $this->container->getParameter("app_files.FileDir");
        $waterMarkName = $this->container->getParameter("app_files.waterMarkName");
        $pathCreator = File::getPathRoot() . $filePaths;

        if (is_object($file)) {
            $type = $file->getMimeType();

            if (strpos($type, "image") !== false)
                $name = $imageDir;
            else
                $name = $fileDir;

            $orginalName = $file->getClientOriginalName();

            $orginalName = explode(".", $orginalName);
            $extention = '.' . strtolower($orginalName[count($orginalName) - 1]);
        }else {
            return null;
        }

        $filePaths.= $name . "/" . $context;

        // CREATE MODEL FILE    
        $modFiles = new File();
        $modFiles->setName($name);
        $modFiles->setOrginName($file->getClientOriginalName());
        $modFiles->setExtention($extention);
        $modFiles->setSize($file->getSize());
        $modFiles->setMimeType($file->getMimeType());
        $modFiles->setTitle($title);
        $modFiles->setFileType(File::getContentTypeDir($file->getMimeType()));
        $modFiles->setContext($context);
        //$this->Save($modFiles);
        $this->container->get("repo.files")->save($modFiles);


        $fileId = $modFiles->getId();
        $pathCreator .= $name . "/" . $context . "/" . $pagesId . "/" . $fileId . "/";
        $filePath = $pathCreator;

        $pathCreator .= Thumbnail::$ImageSizeOriginal . "/";

        if (!file_exists($pathCreator)) {
            if (!mkdir($pathCreator, 0777, true)) {
                throw new \Exception('dir not created');
            }
        }

        $webPath = "/" . $filePaths . "/" . $pagesId . "/" . $fileId . "/" . Thumbnail::$ImageSizeOriginal . "/" . $name . $extention;
        $pathCreator .= $name . $extention;
        $fullpath = $pathCreator;

        // UPDATE MODEL FILE   
        $modFiles->setFilePath($filePath);
        $modFiles->setPath($webPath);
        $modFiles->setFullPath($fullpath);
        $modFiles->setSequence($modFiles->getId());
        $this->container->get("repo.files")->save($modFiles);


        if ($modFiles->getFileType() == File::$FileTypeImages) {

            if ($waterMark) {
                $filePaths = $this->container->getParameter("app_files.filePath");
                $waterMarkPath = File::getPathRoot() . $filePaths . $waterMarkName;

                $wm = $this->imagine->open($waterMarkPath);
                $image = $this->imagine->open($file->getRealPath());
                $x = 0;
                $y = 0;
                $point = new Point($x, $y); // position on image

                $image->paste($wm, $point);
                $image->save($modFiles->getFullPath());
            } else {
                $this->imagine->open($file->getRealPath())->save($modFiles->getFullPath());
            }

            $this->UploadThumbnails($modFiles, $filePaths . "/" . $pagesId . "/" . $fileId . "/", $context);

            return $modFiles;
        } else {
            if (file_exists($file->getRealPath()))
                move_uploaded_file($file->getRealPath(), $pathCreator);
        }

        return $modFiles;
    }

    /**
    * UploadThumbnails files
    *
    * @param File $modFiles
    * @param string $webPath
    * @param string $context
    * @throws \Exception
    */
    public function UploadThumbnails($modFiles, $webPath, $context) {

        $contexts = $this->container->getParameter("app_files.contexts");
        $formats = ($contexts[$context]) ? ($contexts[$context]["formats"]) : $contexts[File::$FileContextDefault]["formats"];

        if ($formats)
            foreach ($formats as $key => $value) {
                $thumbPath = $modFiles->getFilePath();

                $_webPath = "/" . $webPath . $key . "/" . $modFiles->getName() . $modFiles->getExtention();

                $original = false;
                if ($key == "original")
                    $original = true;

                $width = $value["width"];
                $height = $value["height"];

                $thumbPath .= $key . "/";
                if (!file_exists($thumbPath)) {
                    if (!mkdir($thumbPath, 0777, true)) {
                        throw new \Exception('dir not created');
                    }
                }

                $thumbPath .= $modFiles->getName() . $modFiles->getExtention();

                $options = array(
                    'resolution-units' => ImageInterface::RESOLUTION_PIXELSPERINCH,
                    'quality' => $value["quality"],
                );

                $image = $this->imagine->open($modFiles->getFullPath());

                $imgWidth = $image->getSize()->getWidth();
                $imgHeight = $image->getSize()->getHeight();


                if ((!$width) && (!$height)) {
                    $width = $imgWidth;
                    $height = $imgHeight;
                } else if (($width) && (!$height)) {
                    $height = $imgHeight * $width / $imgWidth;
                } else if ((!$width) && ($height)) {
                    $width = $imgWidth * $height / $imgHeight;
                }


                if ($value["type"] == "crop") {
                    // resize Croped image                        
                    //$image->thumbnail(new Box($width, $height))
                    $image->rotate($value["rotate"])
                            ->crop(new Point(0, 0), new Box($width, $height))
                            ->save($thumbPath, $options);

                    $this->container->get("repo.thumbnail")->SaveThumbnails($modFiles, $thumbPath, true, $original, $width, $height, $key, $_webPath);
                } else if ($value["type"] == "simple") {
                    // resize Image                                                                                                                      
                    $image->thumbnail(new Box($width, $height))
                            //->resize(new Box($width, $height))
                            ->rotate($value["rotate"])
                            //->crop(new Point(0, 0), new Box($width, $height))                            
                            ->save($thumbPath);


                    $this->container->get("repo.thumbnail")->SaveThumbnails($modFiles, $thumbPath, false, $original, $width, $height, $key, $_webPath);
                }
            }
    }
}
