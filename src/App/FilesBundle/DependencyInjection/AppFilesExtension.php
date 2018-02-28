<?php

namespace App\FilesBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\Definition\Processor;

class AppFilesExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('app_files.config', $config);
        $container->setParameter('app_files.driver', $config['driver']);
        $container->setParameter('app_files.contexts', $config['contexts']);
        $container->setParameter('app_files.filePath', $config['filePath']);
        $container->setParameter('app_files.waterMarkName', $config['waterMarkName']);
        $container->setParameter('app_files.ImageDir', $config['ImageDir']);
        $container->setParameter('app_files.FileDir', $config['FileDir']);
        
        
//        $driver = 'gd';
//        if (isset($config['driver'])) 
//            $driver = strtolower($config['driver']);        
//
//        if (!in_array($driver, array('gd', 'imagick', 'gmagick'))) 
//            throw new \InvalidArgumentException('Invalid imagine driver specified');        
//
//        $container->setAlias('imagine', new Alias('imagine.'.$driver));
        
        $driverClass = "Imagine\Gd\Imagine";
        
        if ($config['driver'] == 'imagick')
            $driverClass =  "Imagine\Imagick\Imagine";            
        else if ($config['driver'] == 'gmagick') 
            $driverClass =  "Imagine\Gmagick\Imagine"; 
        
        
        $container->setParameter('app_files.driver.class', $driverClass);
        
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');                
        
    }
    
}
