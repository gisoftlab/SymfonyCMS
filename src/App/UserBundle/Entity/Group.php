<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\UserBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * Group entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>
 * 
 * @ORM\Table(name="user__group")
 * @ORM\Entity(repositoryClass="App\UserBundle\Repository\GroupRepository")
 */
class Group extends BaseGroup {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\UserBundle\Entity\User", mappedBy="groups")
     * 
     */
    protected $users;

    public function __toString() {
        return $this->getName();
    }

}
