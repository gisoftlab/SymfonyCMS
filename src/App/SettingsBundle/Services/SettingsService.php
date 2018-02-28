<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */
namespace App\SettingsBundle\Services;

use Symfony\Component\HttpFoundation\RequestStack as Request;
use Doctrine\ORM\EntityManager;
use App\SettingsBundle\Entity\Settings;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Backend Settings service.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>
 */
class SettingsService
{

    protected $container;
    protected $request;
    protected $em;
    protected $namespace = 'AppSettingsBundle';

    /**
     * SettingsService constructor.
     * @param Request $request
     * @param EntityManager $em
     * @param ContainerInterface $container
     */
    public function __construct(Request $request, EntityManager $em, ContainerInterface $container)
    {
        $this->request = $request;
        $this->container = $container;
        $this->em = $em;
    }

    /**
     * GET Request
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * GET Container
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * GET setting value by name
     *
     * string $name
     * @return $value
     */
    public function get($name)
    {
        /**
         *  chain cached settings
         */
        $cached = $this->container->get("cache.chain")->getItem('settings-'.$name);
        if (!$cached->isHit()) {
            $entity = $this->container->get("repo.settings")->findOneBy(array("name" => $name));
            $cached->set($entity);
            $this->container->get("cache.chain")->save($cached);
        } else {
            $entity = $cached->get();
        }


        return ($entity) ? $entity->getValue() : false;


    }

    /**
     * GET All parameters
     *
     * @return Array
     */
    public function all()
    {
        /**
         *  chain cached settings
         */
        $cached = $this->container->get("cache.chain")->getItem('settings-all');
        if (!$cached->isHit()) {
            $entities = $this->container->get("repo.settings")->findAll();
            $cached->set($entities);
            $this->container->get("cache.chain")->save($cached);
        } else {
            $entities = $cached->get();
        }


        $settings = array();

        foreach ($entities as $key => $value) {
            $settings[$value->getName()] = $value->getValue();
        }

        return $settings;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'services.settings';
    }

}
