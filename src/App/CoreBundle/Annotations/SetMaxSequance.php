<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\CoreBundle\Annotations;

use Doctrine\Common\Annotations\Annotation;
 
/**
 * @Annotation
 */
class SetMaxSequance extends Annotation
{
    public $value;
 
}

