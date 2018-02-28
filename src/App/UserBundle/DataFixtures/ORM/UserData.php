<?php

namespace App\UserBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use App\UserBundle\Entity\User;

class UserData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        
               
        $user = new User();
        $user->setUsername('damian');
        $user->setFirstname('Damian');
        $user->setLastname('Damian');
        $user->setPlainPassword('1234');
        $user->setEnabled(1);        
        $user->setEmail('ostraszewski@gmail.com');
        $encoder = new MessageDigestPasswordEncoder('md5');
        $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($password);

        $team = $manager->merge($this->getReference('team_admin'));
        $user->addGroup($team);

        $manager->persist($user);
        $manager->flush();
        
        $this->addReference('user_admin', $user);               
        
        $user = new User();
        $user->setUsername('user');
        $user->setFirstname('user');
        $user->setLastname('Damian');
        $user->setPlainPassword('1234');
        $user->setEnabled(1);        
        $user->setEmail('info@gisoft.pl');
        $encoder = new MessageDigestPasswordEncoder('md5');
        $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($password);

        $team = $manager->merge($this->getReference('team_user'));
        $user->addGroup($team);

        $manager->persist($user);
        $manager->flush();
        
        $this->addReference('user_user', $user);      
        
        $user = new User();
        $user->setUsername('cooperator');
        $user->setFirstname('user');
        $user->setLastname('Damian');
        $user->setPlainPassword('1234');
        $user->setEnabled(1);        
        $user->setEmail('ostraszewski@o2.pl');
        $encoder = new MessageDigestPasswordEncoder('md5');
        $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($password);

        $team = $manager->merge($this->getReference('team_cooperator'));
        $user->addGroup($team);

        $manager->persist($user);
        $manager->flush();
        
        $this->addReference('user_cooperator', $user);  
    }

    public function getOrder() {
        return 2; // the order in which fixtures will be loaded
    }

}
