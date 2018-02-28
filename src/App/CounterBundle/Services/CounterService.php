<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\CounterBundle\Services;

use App\CounterBundle\Entity\Country;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\RequestStack as Request;
use App\CounterBundle\Entity\Counter as Counter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use GeoIp2\Model\City;

/**
 * Backend Counter Service
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 */
class CounterService {

    protected $container;
    protected $request;
    protected $em;
    protected $namespace = 'AppCouterBundle';

    /**
     * CounterService constructor.
     * @param Request $request
     * @param RegistryInterface $doctrine
     * @param ContainerInterface $container
     */
    public function __construct(RequestStack $request, RegistryInterface $doctrine, ContainerInterface $container) {
        $this->request = $request->getMasterRequest();
        $this->container = $container;
        $this->em = $doctrine->getManager();
    }

    /**
     * GET  Request
     *    
     * @return Request $request     
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * GET  Container
     *    
     * @return ContainerInterface $container
     */
    public function getContainer() {
        return $this->container;
    }

    /**
     * Detect Browser 
     *
     * @return array
     */
    public static function detectBrowser() {
        $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $boot = 0;
        // Identify the browser. Check Opera and Safari first in case of spoof. Let Google Chrome be identified as Safari. 

        if (strpos($userAgent, "boot") !== false)
            $boot = 1;

        // What version? 
        if (preg_match('/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/', $userAgent, $matches)) {
            $version = $matches[1];
        } else {
            $version = 'unknown';
        }

        if (strpos($userAgent, "opera") !== false) {
            $array = explode("version/", $userAgent);
            if (isset($array[1]))
                $version = $array[1];
            $name = 'opera';
        }
        elseif (preg_match('/webkit/', $userAgent)) {
            if (strpos($userAgent, "chrome") !== false)
                $name = 'chrome';
            else
                $name = 'safari';
        }
        elseif (strpos($userAgent, "msie") !== false)
            $name = 'msie';
        elseif (preg_match('/mozilla/', $userAgent) && !preg_match('/compatible/', $userAgent))
            $name = 'mozilla';
        else
            $name = 'unrecognized';

        // Running on what platform? 
        if (preg_match('/linux/', $userAgent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/', $userAgent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/', $userAgent)) {
            $platform = 'windows';
        } else {
            $platform = 'unrecognized';
        }

        return array(
            'name' => $name,
            'version' => $version,
            'platform' => $platform,
            'userAgent' => $userAgent,
            'boot' => $boot
        );
    }

    /**
     * COUNT Visitors
     *
     */
    public function CountVisit() {
        setcookie("page_visited_already", "", time() - (3600 * 24));  /* expire in 1 hour * 24 = day */
        // count only one hit
        // $user = $this->container->get('security.token_storage')->getToken()->getUser();


        if (!$this->container->get('session')->has("page_visited_already")) {

            if (!isset($_COOKIE['page_visited_already'])) {
                $browser = self::detectBrowser();

                /**
                 * @var City $localization
                 */

                $localization = $this->container->get('app.geoip')->getIPDbCity();
                
                /**
                 * @var Counter $dt
                 */
                $dt = $this->container->get('repo.counter')->getLast();
                if ($dt) {
                    $data = new Counter();
                    $data->setCountMeter(intval($dt->getCountMeter()) + 1);
                    $data->setDomain($_SERVER["SERVER_NAME"]);
                    $data->setIP($_SERVER['REMOTE_ADDR']);
                    $data->setBrowser($browser["name"]);
                    $data->setBrowserVersion($browser["version"]);
                    $data->setPlatform($browser["platform"]);
                    $data->setUserAgent($browser["userAgent"]);
                    $data->setBoot($browser["boot"]);
                    if ($localization) {
                        $data->setCountry($localization->country->name);
                        $data->setISO($localization->country->isoCode);
                    }
                    $data->setCreatedAt(new \DateTime());
                    $data->setCreatedAtValue();
                    $this->container->get("repo.counter")->save($data);
                } else {
                    $data = new Counter();
                    $data->setCountMeter(1);
                    $data->setDomain($_SERVER["SERVER_NAME"]);
                    $data->setIP($_SERVER['REMOTE_ADDR']);
                    $data->setBrowser($browser["name"]);
                    $data->setBrowserVersion($browser["version"]);
                    $data->setPlatform($browser["platform"]);
                    $data->setUserAgent($browser["userAgent"]);
                    $data->setBoot($browser["boot"]);
                    if ($localization) {
                        $data->setCreatedAt(new \DateTime());
                        $data->setCountry($localization->country->name);
                        $data->setISO($localization->country->isoCode);
                    }
                    $data->setCreatedAtValue();
                    $this->container->get("repo.counter")->save($data);
                }

                // save 
                $this->container->get('session')->set("page_visited_already", 1);

                setcookie("page_visited_already", "1");
            }
        }
    }

}
