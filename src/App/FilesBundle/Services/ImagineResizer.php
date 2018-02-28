<?php

/*
 * Imagine documentation
    http://imagine.readthedocs.org/en/latest/index.html
    http://imagine.readthedocs.org/en/latest/usage/introduction.html#open-existing-images
    
*/ 
namespace  App\FilesBundle\Services;

use Imagine\Image\ImageInterface;
use Symfony\Component\HttpFoundation\File\File;
use Imagine\Image\ImagineInterface;
use Imagine\Image\BoxInterface;
use Imagine\Image\Point;
use Imagine\Image\Box;
 
class ImagineResizer
{
    protected $imagine;
    protected $mode;
    protected $box;

    /**
     * ImagineResizer constructor.
     * @param ImagineInterface $imagine
     * @param BoxInterface $box
     * @param string $mode
     */
    public function __construct(ImagineInterface $imagine, BoxInterface $box, $mode = ImageInterface::THUMBNAIL_OUTBOUND) {
        $this->imagine = $imagine;
        $this->mode = $mode;
        $this->box = $box;
    }

    /**
     * Resize
     *
     * @param File $file
     * @param $destination
     * @return string
     */
    public function resize(File $file, $destination)
    {
        $file->move($destination, $file->getFilename());
        $filename = $file->getFilename();
        $image = $this->imagine->open($destination . '/' . $filename);
        //original size
        $srcBox = $image->getSize();
        //we scale on the smaller dimension
        if ($srcBox->getWidth() > $srcBox->getHeight()) {
            $width  = $srcBox->getWidth()*($this->box->getHeight()/$srcBox->getHeight());
            $height =  $this->box->getHeight();
            //we center the crop in relation to the width
            $cropPoint = new Point((max($width - $this->box->getWidth(), 0))/2, 0);
        } else {
            $width  = $this->box->getWidth();
            $height =  $srcBox->getHeight()*($this->box->getWidth()/$srcBox->getWidth());
            //we center the crop in relation to the height
            $cropPoint = new Point(0, (max($height-$this->box->getHeight(),0))/2);
        }
 
        $box = new Box($width, $height);
        //we scale the image to make the smaller dimension fit our resize box
        $image = $image->thumbnail($box, ImageInterface::THUMBNAIL_OUTBOUND);
         
        //and crop exactly to the box
        $image->crop($cropPoint, $this->box)
        ->save($destination . '/' . $filename);
 
        return $file->getFilename();
    }
}