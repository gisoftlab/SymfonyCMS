<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\CounterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\UniqueConstraint as UniqueConstraint;
use App\CoreBundle\Annotations as CoreAnnotation;

/**
 * Country entity.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>
 *
 * @ORM\Table(name="counter__country",uniqueConstraints={
 *          @UniqueConstraint(name="iso_idx",columns={"iso"}),
 *          @UniqueConstraint(name="name_idx",columns={"name","printable_name"})
 * })
 * @ORM\Entity(repositoryClass="App\CounterBundle\Repository\CountryRepository")  
 * @ORM\HasLifecycleCallbacks()
 */
class Country {

    /**
     * @ORM\Id
     * @ORM\Column(name="iso", type="string", length=2, nullable=false)     
     */
    private $iso;

    /**
     * @var string $name
     *      
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     */
    private $name;

    /**
      /* @var string $printableName
     *      
     * @ORM\Column(name="printable_name", type="string", length=80, nullable=false)
     */
    private $printableName;

    /**
     * @var string $iso3
     * 
     * @ORM\Column(name="iso3", type="string", length=3, nullable=false)
     */
    private $iso3;

    /**
     * @var string $language
     * 
     * @ORM\Column(name="language", type="string", length=2, nullable=true)
     */
    private $language;

    /**
     * @var integer
     * @ORM\Column(name="numcode", type="integer", nullable=false)
     */
    private $numcode = 0;

    public function __construct() {}

    public function __toString() {
        return $this->getPrintableName();
    }

    /**
     * Get iso
     *
     * @return string 
     */
    public function getISO() {
        return $this->iso;
    }

    /**
     * Set iso
     *
     * @param string $iso
     */
    public function setISO($iso) {
        $this->iso = $iso;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set printableName
     *
     * @param string $printableName
     * @return $this
     */
    public function setPrintableName($printableName) {
        $this->printableName = $printableName;

        return $this;
    }

    /**
     * Get ISO3
     *
     * @return string 
     */
    public function getPrintableName() {
        return $this->printableName;
    }

    /**
     * Set ISO3
     *
     * @param string $ISO3
     * @return $this
     */
    public function setISO3($ISO3) {
        $this->iso3 = $ISO3;

        return $this;
    }

    /**
     * Get ISO3
     *
     * @return string 
     */
    public function getISO3() {
        return $this->iso3;
    }

    /**
     * Set numcode
     *
     * @param string $numcode
     * @return $this
     */
    public function setNumcode($numcode) {
        $this->numcode = $numcode;

        return $this;
    }

    /**
     * Get numcode
     *
     * @return string 
     */
    public function getNumcode() {
        return $this->numcode;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return $this
     */
    public function setLanguage($language) {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage() {
        return $this->language;
    }

//    public function getFlag() {
//        $flag_path = sfConfig::get('sf_web_dir') . DIRECTORY_SEPARATOR . 'images/flags/' . strtolower($this->geIso()) . '.png';
//        if (!file_exists($flag_path))
//            return false;
//        return 'images/flags/' . strtolower($this->geIso()) . '.png';
//    }

    public static function inet_aton($ip) {
        $ip = explode('.', $ip);
        //return 2130706433;
        return $ip[0] * pow(256, 3) + $ip[1] * pow(256, 2) + $ip[2] * pow(256, 1) + $ip[3];
    }

}
