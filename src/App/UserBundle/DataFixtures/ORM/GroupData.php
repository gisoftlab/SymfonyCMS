<?php

namespace App\UserBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\UserBundle\Entity\Group;

class GroupData extends AbstractFixture implements OrderedFixtureInterface 
{
    public function load(ObjectManager $manager)
    {
        $team_user          = new Group('User',array('ROLE_USER'));
        $team_cooperator = new Group('Cooperator',array('ROLE_COOPERATOR'));
        $team_admin        = new Group('Admin',array('ROLE_ADMIN'));
                
        $manager->persist($team_user);        
        $manager->persist($team_cooperator);        
        $manager->persist($team_admin);
                        
        $manager->flush();

        $this->addReference('team_admin', $team_admin);
        $this->addReference('team_user', $team_user);
        $this->addReference('team_cooperator', $team_cooperator);        
    }

    public function getOrder() {
        return 1; // the order in which fixtures will be loaded
    }

}
