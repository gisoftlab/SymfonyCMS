<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\CounterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\CoreBundle\Annotations as CoreAnnotation;

/**
 * Counter entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>
 *
 * @ORM\Table(name="counter__visitors")
 * @ORM\Entity(repositoryClass="App\CounterBundle\Repository\CounterRepository")  
 * @ORM\HasLifecycleCallbacks()
 */
class Counter {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer $countMeter
     * 
     * @ORM\Column(name="count_meter", type="bigint", nullable=false)
     */
    private $countMeter = 0;

    /**
     * @var integer $boot
     * 
     * @ORM\Column(name="boot", type="integer", nullable=false)
     */
    private $boot = 0;

    /**
     * @var string $domain
     * 
     * @ORM\Column(name="domain", type="string", length=100, nullable=true)
     */
    private $domain;

    /**
     * @var string $IP
     * 
     * @ORM\Column(name="IP", type="string", length=50, nullable=true)
     */
    private $IP;

    /**
     * @var string $browser
     * 
     * @ORM\Column(name="browser", type="string", length=20, nullable=true)
     */
    private $browser;

    /**
     * @var string $browserVersion
     * 
     * @ORM\Column(name="browser_version", type="string", length=10, nullable=true)
     */
    private $browserVersion;

    /**
     * @var string $platform
     * 
     * @ORM\Column(name="platform", type="string", length=20, nullable=true)
     */
    private $platform;

    /**
     * @var string $userAgent
     * 
     * @ORM\Column(name="user_agent", type="string", length=255, nullable=true)
     */
    private $userAgent;

    /**
     * @var string $country
     * 
     * @ORM\Column(name="country", type="string", length=20, nullable=true)
     */
    private $country;

    /**
     * @var string $ISO
     * 
     * @ORM\Column(name="ISO", type="string", length=2, nullable=true)
     */
    private $ISO;

    /**
     * @var string $ISO3
     * 
     * @ORM\Column(name="ISO3", type="string", length=3, nullable=true)
     */
    private $ISO3;

    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    public function __construct() {
        
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set countMeter
     *
     * @param integer $countMeter
     * @return $this
     */
    public function setCountMeter($countMeter) {
        $this->countMeter = $countMeter;

        return $this;
    }

    /**
     * Get countMeter
     *
     * @return integer
     */
    public function getCountMeter() {
        return $this->countMeter;
    }

    /**
     * Set countMeter
     *
     * @param integer $boot
     * @return $this
     */
    public function setBoot($boot) {
        $this->boot = $boot;

        return $this;
    }

    /**
     * Get boot
     *
     * @return integer 
     */
    public function getBoot() {
        return $this->boot;
    }

    /**
     * Set domain
     *
     * @param string $domain
     * @return $this
     */
    public function setDomain($domain) {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return string 
     */
    public function getDomain() {
        return $this->domain;
    }

    /**
     * Set IP
     *
     * @param string $IP
     * @return $this
     */
    public function setIP($IP) {
        $this->IP = $IP;

        return $this;
    }

    /**
     * Get IP
     *
     * @return string 
     */
    public function getIP() {
        return $this->IP;
    }

    /**
     * Set browser
     *
     * @param string $browser
     * @return $this
     */
    public function setBrowser($browser) {
        $this->browser = $browser;

        return $this;
    }

    /**
     * Get browser
     *
     * @return string 
     */
    public function getBrowser() {
        return $this->browser;
    }

    /**
     * Set browserVersion
     *
     * @param string $browserVersion
     * @return $this
     */
    public function setBrowserVersion($browserVersion) {
        $this->browserVersion = $browserVersion;

        return $this;
    }

    /**
     * Get browserVersion
     *
     * @return string 
     */
    public function getBrowserVersion() {
        return $this->browserVersion;
    }

    /**
     * Set platform
     *
     * @param string $platform
     * @return $this
     */
    public function setPlatform($platform) {
        $this->platform = $platform;

        return $this;
    }

    /**
     * Get platform
     *
     * @return string 
     */
    public function getPlatform() {
        return $this->platform;
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent
     * @return $this
     */
    public function setUserAgent($userAgent) {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get userAgent
     *
     * @return string 
     */
    public function getUserAgent() {
        return $this->userAgent;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return $this
     */
    public function setCountry($country) {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Set ISO
     *
     * @param string $ISO
     * @return $this
     */
    public function setISO($ISO) {
        $this->ISO = $ISO;

        return $this;
    }

    /**
     * Get ISO
     *
     * @return string 
     */
    public function getISO() {
        return $this->ISO;
    }

    /**
     * Set ISO3
     *
     * @param string $ISO3
     * @return $this
     */
    public function setISO3($ISO3) {
        $this->ISO3 = $ISO3;

        return $this;
    }

    /**
     * Get ISO3
     *
     * @return string 
     */
    public function getISO3() {
        return $this->ISO3;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
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
