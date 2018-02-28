<?php

namespace App\UserBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\SettingsBundle\Entity\Settings;

class SettingsData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        
               
        $entity = new Settings();
        
        $entity->setValue("slogan");
        $entity->setValue("Twój slogan");
        
        $entity->setValue("company_name");
        $entity->setValue("Nazwa Firmy");
        
        $entity->setValue("company_host");
        $entity->setValue("www.companyhost.pl");
        
        $entity->setValue("first_name");
        $entity->setValue("Imie");
        
        $entity->setValue("last_name");
        $entity->setValue("Nazwisko");
        
        $entity->setValue("email_main");
        $entity->setValue("info@gisoft.pl");  
        
        $entity->setValue("email");
        $entity->setValue("ostraszewski@o2.pl");  
        
        $entity->setValue("phone");
        $entity->setValue("telefin");  
        
        $entity->setValue("culture");
        $entity->setValue("pl_PL");  
        
        $entity->setValue("lang");
        $entity->setValue("pl");  
                
        $manager->persist($entity);
        $manager->flush();     
    }

    public function getOrder() {
        return 2; // the order in which fixtures will be loaded
    }

}
