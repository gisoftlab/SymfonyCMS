<?php

namespace App\PagesBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\PagesBundle\Entity\Category as Category;

class CategoryData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {

        $block = new Category();
        $block->setName('Menu');
        $manager->persist($block);
                 
         $this->addReference('page_block_menu', $block);
         
        $block = new Category();
        $block->setName('Footer');
        $manager->persist($block);
                 
         $this->addReference('page_block_footer', $block);
 
        
        $manager->flush();       
        
                                                                                                
    }

    public function getOrder() {
        return 5; // the order in which fixtures will be loaded
    }

}
