<?php

namespace App\CoreBundle\Services;


class AggregatorFactory {

    public static function initialize()
    {
        return AggregatorAssetics::getInstance();
    }
}
