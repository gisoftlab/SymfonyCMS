<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\UserBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * User entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>
 * 
 * @ORM\Table(name="user__user")
 * @ORM\Entity(repositoryClass="App\UserBundle\Repository\UserRepository")
 * @UniqueEntity("email")
 * @UniqueEntity("username") 
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser implements UserInterface {

    const GENDER_FEMALE = 1;
    const GENDER_MALE = 2;
    const GROUP_USER = 1;
    const GROUP_COOPERATOR = 2;
    const GROUP_ADMINISTRAOR = 3;
    const PRIVILAGE_USER = "User";
    const PRIVILAGE_ADMIN = "Admin";
    const PRIVILAGE_COOPERATOR = "Cooperator";

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $username
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[a-z0-9_]+$/", message="Nazwa użytkownika może zawierać cyfry (0-9), małe litery (a-z), łączniki (_)")
     *
     */
    protected $username;

    /**
     * @ORM\ManyToMany(targetEntity="App\UserBundle\Entity\Group", inversedBy="users", cascade={"persist"})
     * @ORM\JoinTable(name="user__user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @var string $firstname
     *
     * @ORM\Column(name="firstname", type="string", length=255, unique=false, nullable=true)
     *
     */
    protected $firstname;

    /**
     * @var string $lastname
     *
     * @ORM\Column(name="lastname", type="string", length=255, unique=false, nullable=true)
     *
     */
    protected $lastname;

    /**
     * @var string $createdIp
     *
     * @ORM\Column(name="created_ip", type="string", length=255, unique=false, nullable=true)
     */
    protected $createdIp;

    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;


    /**
     * @var string $website
     *
     * @ORM\Column(name="website", type="string", nullable=true)
     */
    protected $website;

    /**
     * @var string $gender
     *
     * @ORM\Column(name="gender", type="string", nullable=true)
     */
    protected $gender;

    /**
     * @var string $locale
     *
     * @ORM\Column(name="locale", type="string", nullable=true)
     */
    protected $locale;

    /**
     * @var string $timezone
     *
     * @ORM\Column(name="timezone", type="string", nullable=true)
     */
    protected $timezone;

    /**
     * @var string $phone
     *
     * @ORM\Column(name="phone", type="string", nullable=true)
     */
    protected $phone;

    /**
     * @var boolean $isSuperAdmin
     *
     * @ORM\Column(name="is_super_admin", type="boolean", nullable=true)
     */
    private $isSuperAdmin = 0;

    /**
     * @var string $token
     *
     * @ORM\Column(name="token", type="string", nullable=true)
     */
    protected $token;

    /**
     * User constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     */
    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    /**
     * Get firstname
     *
     * @return string $firstname
     */
    public function getFirstname() {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     */
    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    /**
     * Get lastname
     *
     * @return string $lastname
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender) {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale) {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale() {
        return $this->locale;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone) {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * @param string $timezone
     */
    public function setTimezone($timezone) {
        $this->timezone = $timezone;
    }

    /**
     * @return string
     */
    public function getTimezone() {
        return $this->timezone;
    }

    /**
     * @param string $website
     */
    public function setWebsite($website) {
        $this->website = $website;
    }

    /**
     * @return string
     */
    public function getWebsite() {
        return $this->website;
    }

    /**
     * @param string $isSuperAdmin
     */
    public function setIsSuperAdmin($isSuperAdmin) {
        $this->isSuperAdmin = $isSuperAdmin;
    }

    /**
     * @return string
     */
    public function getIsSuperAdmin() {
        return $this->isSuperAdmin;
    }

    /**
     * @param string $token
     */
    public function setToken($token) {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * Sets the creation date
     *
     * @param \DateTime|null $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt = null) {
        $this->createdAt = $createdAt;
    }

    /**
     * Returns the creation date
     *
     * @return \DateTime|null
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Sets the last update date
     *
     * @param \DateTime|null $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt = null) {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Returns the last update date
     *
     * @return \DateTime|null
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * Returns the expiration date
     *
     * @return \DateTime|null
     */
    public function getExpiresAt() {
        return $this->expiresAt;
    }

    /**
     * Returns a string representation
     *
     * @return string
     */
    public function __toString() {
        return $this->getUsername() ? : '-';
    }

    /**
     * Sets the user groups
     *
     * @param array $groups
     */
    public function setGroups($groups) {
        foreach ($groups as $group) {
            $this->addGroup($group);
        }
    }

    /**
     * Set createdIp
     *
     * @param string $createdIp
     */
    public function setCreatedIp($createdIp) {
        $this->createdIp = $createdIp;
    }

    /**
     * Get createdIp
     *
     * @return string $createdIp
     */
    public function getCreatedIp() {
        return $this->createdIp;
    }

    /**
     * @return string
     */
    public function getFullname() {
        return sprintf("%s %s", $this->getFirstname(), $this->getLastname());
    }

    public function checkPrivilage($name) {
        foreach ($this->getGroups() as $key => $value) {
            if ($value->getName() == $name)
                return true;
        }
        return false;
    }

    public function isAdmin() {
        if ($this->checkPrivilage(self::PRIVILAGE_ADMIN))
            return true;
        else
            return false;
    }

    public function isUsers() {
        if ($this->checkPrivilage(self::PRIVILAGE_USER))
            return true;
        else
            return false;
    }

    public function isCooperator() {
        if ($this->checkPrivilage(self::PRIVILAGE_COOPERATOR))
            return true;
        else
            return false;
    }

    public function getRole() {
        $roles = array();
        foreach ($this->getGroups() as $group) {
            $roles[] = $group->getName();
        }
        return implode(',', $roles);
    }

}
