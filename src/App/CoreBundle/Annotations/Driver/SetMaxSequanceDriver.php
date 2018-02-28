<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\CoreBundle\Annotations\Driver;

use App\CoreBundle\Annotations\SetMaxSequance;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SetMaxSequanceDriver {

    /**
     * @var Reader
     */
    private $reader;
    /**
     * @var null|RegistryInterface
     */
    private $doctrine = null;

    private function getDoctrine() {
        return $this->doctrine;
    }


    /**
     * SetMaxSequanceDriver constructor.
     * @param Reader $reader
     * @param RegistryInterface $doctrine
     */
    public function __construct(Reader $reader, RegistryInterface $doctrine) {
        // initialise Doctrine Reader
        $this->reader = $reader;        //get annotations reader
        $this->doctrine = $doctrine;   // get doctrine
    }

    /**
     * prePersist
     * @param LifecycleEventArgs $event
     * @throws \ReflectionException
     */
    public function prePersist(LifecycleEventArgs $event) {
        // get current entity
        $entity = $event->getEntity();

        // get Reflection of Class
        $reflectionProperties = new \ReflectionClass($entity);
        // get all properties with their reflections
        $properties = $reflectionProperties->getProperties();

        $property = false;
        // iterate over all properties to check for the @CreatedAt-Annotation
        foreach ($properties as $prop) {
            $annotation = $this->reader->getPropertyAnnotation(
                    $prop, 'App\CoreBundle\Annotations\SetMaxSequance'
            );
            // get the name of the property/field
            if (!empty($annotation)) {
                $property = $prop->getName();
            }
        }

        if (!empty($property)) {
            // getCreatedAt and setCreatedAt
            $setMethod = 'set' . ucFirst($property);
            $getMethod = 'get' . ucFirst($property);
            if (method_exists($entity, $setMethod) && method_exists($entity, $getMethod)) {
                // add the current date if not available
                $date = $entity->{$getMethod}();
                if (empty($date)) {

                    $result = $this->getDoctrine()->getRepository($reflectionProperties->getName())->getMaxSequence();

                    // $entity->{$setMethod}(new \DateTime());
                    $entity->{$setMethod}($result);
                }
            }
        }
    }
}
