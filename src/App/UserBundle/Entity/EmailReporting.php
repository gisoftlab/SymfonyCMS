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
 * EmailReporting entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>
 * 
 * @ORM\Table(name="user__email_reporting")
 * @ORM\Entity(repositoryClass="App\UserBundle\Repository\EmailReportingRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class EmailReporting {

    const STATUS_UNKNOWN = "unknown";
    const TYPE_DEFAULT = "default";
    const TYPE_CONTACT = "contact";
    const TYPE_RESPONSE = "response";
    const TYPE_TESTIMONIAL = "testimonial";

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $host 
     * @ORM\Column(name="host", type="string", length=255, unique=false, nullable=true)
     */
    protected $host = "";

    /**
     * @var integer
     * @ORM\Column(name="port", type="integer")
     */
    protected $port = 21;

    /**
     * @var string $fromEmail
     * @ORM\Column(name="from_email", type="string", length=255, nullable=true)
     */
    protected $fromEmail;

    /**
     * @var string $to 
     * @ORM\Column(name="to_email", type="string", length=255, nullable=true)
     */
    protected $toEmail;

    /**
     * @var string $IP
     *     
     * @ORM\Column(name="ip", type="string", length=128, unique=false, nullable=true)
     */
    protected $IP = 0;

    /**
     * @var string $subject 
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    protected $subject = "";

    /**
     * @var string $body 
     * @ORM\Column(name="body", type="text", nullable=true)
     */
    protected $body = "";

    /**
     * @var string $status 
     * @ORM\Column(name="status", type="string",length=50, nullable=false)
     */
    protected $status = "UNKNOWN";

    /**
     * @var string $type 
     * @ORM\Column(name="type", type="string",length=50, nullable=false)
     */
    protected $type = "DEFAULT";

    /**
     * @var string $errorMessage 
     * @ORM\Column(name="errorMessage", type="string", length=255, nullable=true)
     */
    protected $errorMessage;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id" , onDelete="CASCADE", nullable=true)
     * })
     */
    protected $user = null;

    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * EmailReporting constructor.
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
    public function setUser($user = null) {
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
     * Set host
     *
     * @param string $host
     */
    public function setHost($host) {
        $this->host = $host;
    }

    /**
     * Get userLogin
     *
     * @return string $userLogin
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * Set port
     *
     * @param integer
     */
    public function setPort($port) {
        $this->port = $port;
    }

    /**
     * Get port
     *
     * @return integer 
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * @param string $fromEmail
     */
    public function setFromEmail($fromEmail) {
        $this->fromEmail = $fromEmail;
    }

    /**
     * @return string
     */
    public function getFromEmail() {
        return $this->fromEmail;
    }

    /**
     * @param string $toEmail
     */
    public function setToEmail($toEmail) {
        $this->toEmail = $toEmail;
    }

    /**
     * @return string
     */
    public function getToEmail() {
        return $this->toEmail;
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
     * @param integer $subject
     */
    public function setSubject($subject) {
        $this->subject = $subject;
    }

    /**
     * @return integer
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * @param integer $body
     */
    public function setBody($body) {
        $this->body = $body;
    }

    /**
     * @return integer
     */
    public function getBody() {
        return $this->body;
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
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return string
     */
    public function getErrorMessage() {
        return $this->errorMessage;
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

}
