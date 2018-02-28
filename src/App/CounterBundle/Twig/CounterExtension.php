<?php

namespace App\CounterBundle\Twig;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\UserBundle\Entity\CompanyType as Type;

class CounterExtension extends \Twig_Extension {

    protected $doctrine;
    protected static $context;

    public function __construct(RegistryInterface $doctrine, TokenStorageInterface $context) {
        $this->doctrine = $doctrine;
        self::$context = $context;
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('getCounter', array($this, 'getCounter')),
        );
    }

    /**
     * @return integer
     */
    public function getCounter() {

        if($last = $this->doctrine->getRepository('AppCounterBundle:Counter')->getLast())
            return $last->getCountMeter();

        return 0;
    }

    public function getName() {
        return 'counter_extension';
    }

}
