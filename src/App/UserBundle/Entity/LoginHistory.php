<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\UserBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * LoginHistory entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>
 * 
 * @ORM\Table(name="user__login_history")
 * @ORM\Entity(repositoryClass="App\UserBundle\Repository\LoginHistoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class LoginHistory {

    const STATUS_UNKNOWN = "unknown";
    const STATUS_REGISTER = "register";
    const STATUS_LOGIN = "login";
    const STATUS_LOGOUT = "logout";
    const STATUS_ATTEMPT = "attempt";

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $userLogin 
     * @ORM\Column(name="user_login", type="string", length=255, unique=false, nullable=true)
     */
    protected $userLogin;

    /**
     * @var string $userEmail 
     * @ORM\Column(name="user_email", type="string", length=255, unique=false, nullable=true)
     */
    protected $userEmail;

    /**
     * @var string $sessionId 
     * @ORM\Column(name="session_id", type="string", length=255, nullable=true)
     */
    protected $sessionId;

    /**
     * @var string $IP
     *     
     * @ORM\Column(name="ip", type="string", length=128, unique=false, nullable=true)
     */
    protected $IP;

    /**
     * @var string $header 
     * @ORM\Column(name="header", type="text", nullable=true)
     */
    protected $header;

    /**
     * @var string $status 
     * @ORM\Column(name="status", type="string",length=50, nullable=false)
     */
    protected $status = "UNKNOWN";

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id" , onDelete="CASCADE", nullable=true)
     * })
     */
    protected $user;

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
     * LoginHistory constructor.
     */
    public function __construct() {}

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \App\UserBundle\Entity\User $user
     */
    public function setUser(User $user) {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return \App\UserBundle\Entity\User $user
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set userLogin
     *
     * @param string $userLogin
     */
    public function setUserLogin($userLogin) {
        $this->userLogin = $userLogin;
    }

    /**
     * Get userLogin
     *
     * @return string $userLogin
     */
    public function getUserLogin() {
        return $this->userLogin;
    }

    /**
     * Set userEmail
     *
     * @param string $userEmail
     */
    public function setUserEmail($userEmail) {
        $this->userEmail = $userEmail;
    }

    /**
     * Get userEmail
     *
     * @return string $userEmail
     */
    public function getUserEmail() {
        return $this->userEmail;
    }

    /**
     * @param string $sessionId
     */
    public function setSessionId($sessionId) {
        $this->sessionId = $sessionId;
    }

    /**
     * @return string
     */
    public function getSessionId() {
        return $this->sessionId;
    }

    /**
     * @param integer $IP
     */
    public function setIP($IP) {
        $this->IP = $IP;
    }

    /**
     * @return integer
     */
    public function getIP() {
        return $this->IP;
    }

    /**
     * @param integer $header
     */
    public function setHeader($header) {
        $this->header = $header;
    }

    /**
     * @return integer
     */
    public function getHeader() {
        return $this->header;
    }

    /**
     * @param string $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
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

    // ---  
    // --- LIFECYCLECALLBACKS ACTIONS

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue() {
        if (!$this->getCreatedAt()) {
            $this->createdAt = new \DateTime();
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue() {
        $this->updatedAt = new \DateTime();
    }

}
