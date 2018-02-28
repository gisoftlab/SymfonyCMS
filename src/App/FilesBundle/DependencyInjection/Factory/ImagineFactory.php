<?php

namespace App\FilesBundle\DependencyInjection\Factory;

use Imagine\Gd\Imagine as ImagineGd;
use Imagine\Imagick\Imagine as ImagineImagick;
use Imagine\Gmagick\Imagine as ImagineGmagick;

class ImagineFactory {

    public static function create($driver) {
        $driver = strtolower($driver);

        if ($driver == 'gd') {
            return new ImagineGd();
        } else if ($driver == 'imagick') {
            return new ImagineImagick();
        } else if ($driver == 'gmagick') {
            return new ImagineGmagick();
        }

        return null;
    }
}