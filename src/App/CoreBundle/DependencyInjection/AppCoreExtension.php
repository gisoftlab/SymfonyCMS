<?php
namespace App\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class AppCoreExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        //$configuration = new Configuration();
        //$config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('services_gedmo.yml');
        $loader->load('services_listener.yml');
        $loader->load('services_aggregator.yml');
        $loader->load('services_memcached.yml');
        $loader->load('services_cache.yml');
        $loader->load('services_twig.yml');
    }
}
